<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $list = Order::query()
        ->withCount(['orderItems'])
        ->with(['user'])
        ->orderBy('id', 'desc')
        ->paginate($request->get('pageSize', config('app.pageSize')));
        return $this->success($list);
    }

    public function show(Order $order)
    {
        return $this->success($order->load(['orderItems.productSku.product']));
    }

    public function delivery(Request $request, OrderItem $orderItem)
    {
        $params = $request->only(['express_company_name', 'express_no']);
        $order = $orderItem->order;
        if ($order->status !== Order::STATUS_PAID) {
            return $this->failed('订单状态错误');
        }

        $orderItem->fill($params);
        $orderItem->status = Order::STATUS_DELIVERIED;
        $orderItem->delivery_time = Carbon::now();
        $orderItem->save();

        $currentItems = $order->orderItems;

        $isDelivery = true;
        if ($currentItems && count($currentItems)) {
            foreach ($currentItems as $item) {
                if ($item->status !== Order::STATUS_DELIVERIED) {
                    $isDelivery = false;
                }
            }
        }
        if ($isDelivery) {
            $order->status = Order::STATUS_DELIVERIED;
            $order->delivery_time = Carbon::now();
            $order->save();
        }

        return $this->message('操作成功');
    }

    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();

        return $this->message('操作成功');
    }

    public function statusMapping()
    {
        $list = Order::$statusMap;
        return $this->success($list);
    }
}
