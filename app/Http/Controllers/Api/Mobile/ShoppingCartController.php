<?php
/**
 * This file is part of the finance
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCompanyMapping;
use App\Models\ProductSku;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingCartController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('mobile')->user();

        $total = ShoppingCart::query()->where(['user_id' => $user->id])->sum('total');

        $query = ShoppingCart::query()->with(['product', 'productSku'])->where(['user_id' => $user->id])->orderBy('id', 'desc');
        $paginate = $request->input('paginate', 1);
        $list = $paginate ? $query->paginate($request->get('pageSize', config('app.pageSize'))) : $query->get();

        return $this->success([
            'total' => $total,
            'list' => $list
        ]);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'product_id' => 'required|integer',
            'product_sku_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $product = Product::query()->where(['status' => 1, 'id' => $params['product_id']])->first();
        if (!$product) {
            return $this->failed('商品不存在');
        }

        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();
        $mapping = ProductCompanyMapping::query()->where(['product_id' => $product->id, 'company_id' => $company->id])->first();
        if (!$mapping) {
            return $this->failed('商品不存在');
        }

        $productSku = ProductSku::query()->where(['product_id' => $product->id, 'id' => $params['product_sku_id']])->first();
        if (!$productSku) {
            return $this->failed('SKU不存在');
        }
        $user = auth('mobile')->user();

        $ShoppingCart = ShoppingCart::query()->where(['product_id' => $product->id, 'product_sku_id' => $productSku->id, 'user_id' => $user->id])->first();
        if ($ShoppingCart) {
            $ShoppingCart->increment('quantity', $params['quantity']);

            $ShoppingCart->total = $ShoppingCart->price * $ShoppingCart->quantity;
            $ShoppingCart->save();
        } else {
            ShoppingCart::query()->create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_sku_id' => $productSku->id,
                'quantity' => $params['quantity'],
                'price' => $productSku->sale_price,
                'total' => $params['quantity'] * $productSku->sale_price
            ]);
        }

        return $this->message('操作成功');
    }

    public function updateQuantity(Request $request, ShoppingCart $shoppingCart)
    {
        $user = auth('mobile')->user();
        if ($user->id !== $shoppingCart->user_id) {
            return $this->failed('权限错误');
        }

        $params = $this->validate($request, [
            'quantity' => 'required|integer'
        ]);

        $shoppingCart->quantity = $params['quantity'];
        $shoppingCart->total = $params['quantity'] * $shoppingCart->price;
        $shoppingCart->save();

        return $this->message('操作成功');
    }

    public function removeItem(ShoppingCart $shoppingCart)
    {
        $user = auth('mobile')->user();
        if ($user->id !== $shoppingCart->user_id) {
            return $this->failed('权限错误');
        }

        $shoppingCart->delete();

        return $this->message('操作成功');
    }

    public function removeAll()
    {
        $user = auth('mobile')->user();

        DB::transaction(function () use ($user) {
            ShoppingCart::query()->where(['user_id' => $user->id])->delete();
        });

        return $this->message('操作成功');
    }

    public function removeSelect(Request $request)
    {
        $ShoppingCartIds = $request->input('ids');
        if (!$ShoppingCartIds || !is_array($ShoppingCartIds)) {
            return $this->failed('参数错误');
        }
        $user = auth('mobile')->user();
        foreach ($ShoppingCartIds as $id) {
            ShoppingCart::query()->where(['user_id' => $user->id, 'id' => $id])->delete();
        }

        return $this->message('操作成功');
    }
}
