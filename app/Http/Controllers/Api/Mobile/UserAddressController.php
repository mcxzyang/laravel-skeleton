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
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('mobile')->user();

        $list = UserAddress::query()
            ->where(['user_id' => $user->id])->orderBy('id', 'desc')
            ->paginate($request->get('pageSize', config('app.pageSize')));

        return $this->success($list);
    }

    public function store(Request $request, UserAddress $userAddress)
    {
        $this->validate($request, [
            'regions' => 'required|array',
            'contact_name' => 'required',
            'contact_phone' => 'required',
            'address' => 'required'
        ]);

        $params = $request->only([
            'regions', 'address', 'contact_name', 'contact_phone', 'is_default'
        ]);
        $params['province'] = $params['regions'][0];
        $params['city'] = $params['regions'][1];
        $params['area'] = $params['regions'][2];

        $user = auth('mobile')->user();

        $userAddress->fill(array_merge($params, ['user_id' => $user->id]));
        $userAddress->save();
        if ($userAddress->is_default === 1) {
            UserAddress::query()->where(['user_id' => $user->id])->where('id', '!=', $userAddress->id)
                ->update([
                    'is_default' => 0
                ]);
        }

        return $this->message('操作成功');
    }

    public function show(UserAddress $userAddress)
    {
        $user = auth('mobile')->user();
        if ($user->id !== $userAddress->user_id) {
            return $this->failed('权限错误');
        }

        return $this->success($userAddress);
    }

    public function update(Request $request, UserAddress $userAddress)
    {
        $user = auth('mobile')->user();
        if ($user->id !== $userAddress->user_id) {
            return $this->failed('权限错误');
        }

        $params = $request->only([
            'regions', 'address', 'contact_name', 'contact_phone', 'is_default'
        ]);
        if (isset($params['regions']) && count($params['regions'])) {
            $params['province'] = $params['regions'][0];
            $params['city'] = $params['regions'][1];
            $params['area'] = $params['regions'][2];
        }
        $userAddress->fill($params);
        $userAddress->save();

        if ($userAddress->is_default === 1) {
            UserAddress::query()->where(['user_id' => $userAddress->user_id])->where('id', '!=', $userAddress->id)
                ->update([
                    'is_default' => 0
                ]);
        }

        return $this->message('操作成功');
    }

    public function destroy(UserAddress $userAddress)
    {
        $user = auth('mobile')->user();
        if ($user->id !== $userAddress->user_id) {
            return $this->failed('权限错误');
        }

        $userAddress->delete();

        return $this->message('操作成功');
    }
}
