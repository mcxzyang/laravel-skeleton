<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me()
    {
        $user = auth('mobile')->user();

        return $this->success($user);
    }

    public function update(Request $request)
    {
        $params = $request->only(['avatar', 'nickname', 'phone']);

        /**
         * @var \App\Models\User $user
         */
        $user = auth('mobile')->user();

        $user->fill($params);
        $user->save();

        return $this->message('操作成功');
    }
}
