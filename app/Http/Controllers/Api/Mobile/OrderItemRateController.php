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
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemRate;
use Illuminate\Http\Request;

class OrderItemRateController extends Controller
{
    public function store(Request $request, OrderItem $orderItem)
    {
        $order = $orderItem->order;

        $user = auth('mobile')->user();
        if ($user->id !== $order->user_id) {
            return $this->failed('权限错误');
        }
        if ($order->status !== Order::STATUS_FINISHED) {
            return $this->failed('订单状态错误');
        }
        $rates = $order->orderItemRates;
        if ($rates && count($rates)) {
            return $this->failed('您已评价过');
        }
        $params = $this->validate($request, [
            'score' => 'required|numeric',
            'content' => 'required'
        ]);

        $orderItemRate = new OrderItemRate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $orderItem->product_id,
            'status' => 1
        ]);
        $orderItemRate->fill($params);
        $orderItemRate->save();

        return $this->message('操作成功');
    }
}
