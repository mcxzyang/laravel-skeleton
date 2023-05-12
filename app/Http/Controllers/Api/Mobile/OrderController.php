<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderAfterSale;
use App\Models\OrderAfterSaleItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCompanyMapping;
use App\Models\ProductSku;
use App\Models\ShoppingCart;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $params = $request->only(['shopping_cart_id_list', 'user_address_id', 'remark']);

        $user = auth('mobile')->user();

        $inFactShoppingCartIdList = [];
        if (!isset($params['shopping_cart_id_list']) || count($params['shopping_cart_id_list']) <= 0) {
            return $this->failed('无购物车参数');
        }
        foreach ($params['shopping_cart_id_list'] as $shoppingCartItemId) {
            $shoppingCartItem = ShoppingCart::query()->where(['id' => $shoppingCartItemId])->first();
            if (!$shoppingCartItem) {
                continue;
            }
            $productSku = $shoppingCartItem->productSku;
            if ($productSku->stock > 0 && $productSku->stock < $shoppingCartItem->stock) {
                throw new InvalidRequestException('库存不足');
            }
            $inFactShoppingCartIdList[] = $shoppingCartItemId;
        }
        if (count($inFactShoppingCartIdList) <= 0) {
            return $this->failed('无有效的购物车参数');
        }
        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();

        $userAddress = [];
        if (isset($params['user_address_id']) && $params['user_address_id']) {
            $userAddressDetail = UserAddress::query()->where(['user_id' => $user->id, 'id' => $params['user_address_id']])->first();
            if (!$userAddressDetail) {
                return $this->failed('收货地址不存在');
            }
            $userAddress = [
                'province' => $userAddressDetail->province,
                'city' => $userAddressDetail->city,
                'area' => $userAddressDetail->area,
                'address' => $userAddressDetail->address,
                'contact_name' => $userAddressDetail->contact_name,
                'contact_phone' => $userAddressDetail->contact_phone
            ];
        }

        $productOrder = new Order([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'address' => $userAddress,
            'status' => Order::STATUS_PENDING_PAT
        ]);
        $productOrder->fill($params);
        $productOrder->save();

        $total = 0;

        foreach ($inFactShoppingCartIdList as $shoppingCartItemId) {
            $shoppingCartItem = ShoppingCart::query()->where(['id' => $shoppingCartItemId])->first();
            $productSku = $shoppingCartItem->productSku;
            $itemTotal = $productSku->sale_price * $shoppingCartItem->quantity;


            $productOrderItem = new OrderItem([
                'order_id' => $productOrder->id,
                'product_id' => $shoppingCartItem->product_id,
                'product_sku_id' => $shoppingCartItem->product_sku_id,
                'price' => $productSku->sale_price,
                'stock' => $shoppingCartItem->quantity,
                'total' => $itemTotal
            ]);
            $productOrderItem->save();

            if ($productSku->stock > 0) {
                // 减库存
                $productSku->decreaseStock($shoppingCartItem->quantity);
            }

            $total += $itemTotal;
        }
        $productOrder->total = $total;
        $productOrder->save();

        // 删除购物车
        ShoppingCart::query()->whereIn('id', $inFactShoppingCartIdList)->delete();

        // 事件订阅

        return $this->success($productOrder->load(['orderItems.productSku']));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'product_sku_id' => 'required',
            'stock' => 'integer|required',
            'user_address_id' => 'required'
        ]);

        $params = $request->only(['product_id', 'product_sku_id', 'stock', 'user_address_id', 'remark']);

        $user = auth('mobile')->user();

        $product = Product::query()->where(['id' => $params['product_id']])->first();

        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();
        $productCompanyMapping = ProductCompanyMapping::query()->where(['product_id' => $product->id, 'company_id' => $company->id])->first();
        if (!$product || !$productCompanyMapping) {
            return $this->failed('商品不存在');
        }
        $productSku = ProductSku::query()->where(['id' => $params['product_sku_id'], 'product_id' => $params['product_id']])->first();
        if (!$productSku) {
            return $this->failed('商品规格不存在');
        }
        if ($productSku->stock > 0 && $params['stock'] > $productSku->stock) {
            return $this->failed('库存不足');
        }
        $userAddress = [];
        if (isset($params['user_address_id']) && $params['user_address_id']) {
            $userAddressDetail = UserAddress::query()->where(['user_id' => $user->id, 'id' => $params['user_address_id']])->first();
            if (!$userAddressDetail) {
                return $this->failed('收货地址不存在');
            }
            $userAddress = [
                'province' => $userAddressDetail->province,
                'city' => $userAddressDetail->city,
                'area' => $userAddressDetail->area,
                'address' => $userAddressDetail->address,
                'contact_name' => $userAddressDetail->contact_name,
                'contact_phone' => $userAddressDetail->contact_phone
            ];
        }
        $productOrder = new Order([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'address' => $userAddress,
            'status' => Order::STATUS_PENDING_PAT
        ]);
        $productOrder->fill($params);
        $productOrder->save();

        $total = 0;

        $itemTotal = $productSku->sale_price * $params['stock'];

        $productOrderItem = new OrderItem([
            'order_id' => $productOrder->id,
            'product_id' => $productSku->product_id,
            'product_sku_id' => $productSku->id,
            'price' => $productSku->sale_price,
            'stock' => $params['stock'],
            'total' => $itemTotal
        ]);
        $productOrderItem->save();

        $orderType = $product->type;

        if ($productSku->stock > 0) {
            // 减库存
            $productSku->decreaseStock($params['stock']);
        }

        $total += $itemTotal;

        $productOrder->total = $total;
        $productOrder->save();

        // 事件订阅

        return $this->success($productOrder->load(['orderItems.productSku']));
    }

    public function index(Request $request)
    {
        $user = auth('mobile')->user();

        $list = Order::filter($request->all())
        ->with(['orderItems.product', 'orderItems.productSku'])
        ->where(['user_id' => $user->id])
        ->orderBy('id', 'desc')
        ->paginate($request->get('pageSize', config('app.pageSize')));

        return $this->success($list);
    }

    public function show(Order $order)
    {
        $user = auth('mobile')->user();
        if ($order->user_id !== $user->id) {
            return $this->failed('权限错误');
        }
        $order = $order->load(['orderItems.product', 'orderItems.productSku']);

        return $this->success($order);
    }

    public function receive(OrderItem $orderItem)
    {
        $order = $orderItem->order;
        $user = auth('mobile')->user();
        if ($user->id !== $order->user_id) {
            return $this->failed('权限错误');
        }
        if ($order->status !== Order::STATUS_DELIVERIED) {
            return $this->failed('订单状态错误');
        }
        $orderItem->status = Order::STATUS_FINISHED;
        $orderItem->receiving_time = Carbon::now();
        $orderItem->save();

        $currentItems = $order->orderItems;

        $isReceive = true;
        if ($currentItems && count($currentItems)) {
            foreach ($currentItems as $item) {
                if ($item->status !== Order::STATUS_FINISHED) {
                    $isReceive = false;
                }
            }
        }
        if ($isReceive) {
            $order->status = Order::STATUS_FINISHED;
            $order->receiving_time = Carbon::now();
            $order->save();
        }

        return $this->message('操作成功');
    }

    public function afterSale(Request $request, OrderItem $orderItem)
    {
        $params = $this->validate($request, [
            'type' => 'required|in:1,2',
            'reason' => 'required',
            'images' => 'array',
            'remark' => 'max:100'
        ]);
        $user = auth('mobile')->user();
        $order = $orderItem->order;
        if ($user->id !== $order->user_id) {
            return $this->failed('权限错误');
        }
        $result = OrderAfterSale::query()->where(['user_id' => $user->id, 'order_item_id' => $orderItem->id])->first();
        if ($result) {
            return $this->failed('该订单您已申请过售后');
        }
        if ($order->status !== Order::STATUS_FINISHED) {
            return $this->failed('订单状态错误');
        }

        $orderAfterSale = new OrderAfterSale([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'type' => $params['type'],
            'reason' => $params['reason'],
            'price' => $orderItem->total,
            'after_status' => 1
        ]);
        $orderAfterSale->save();

        $orderAfterSaleItem = new OrderAfterSaleItem([
            'user_id' => $user->id,
            'source' => 1,
            'images' => $params['images'] || [],
            'content' => sprintf(
                '买家发起了%s申请,退款商品:%s,货物状态：%s,原因：%s,金额：%s元',
                OrderAfterSale::$typeMap[$params['type']],
                $order->status_text,
                $params['reason'],
                $orderItem->total
            ),
            'remark' => $params['remark']
        ]);
        $orderAfterSaleItem->save();

        return $this->message('操作成功');
    }
}
