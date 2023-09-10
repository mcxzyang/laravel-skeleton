<?php
/**
 * This file is part of the finance
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserResource;
use App\Models\AdminMenu;
use App\Models\AdminRoleMenu;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $params = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha_api:'.request('key').',math'
        ], [
            'captcha.captcha_api' => '验证码错误'
        ]);
        $user = AdminUser::query()->where(['username' => $params['username']])->first();
        if (!$user) {
            return $this->failed('账号不存在');
        }
        if (!\Hash::check($params['password'], $user->password)) {
            return $this->failed('密码不正确');
        }
        $token = auth('admin')->login($user);

        return $this->success($this->respondWithToken($token));
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ];
    }

    public function me()
    {
        $user = auth('admin')->user();
        $user->permissions = ['*'];
        if (!$user->is_super_admin) {

            $menuIds = AdminRoleMenu::query()->whereIn('role_id', $user->roles)->pluck('menu_id');

            $permissions = AdminMenu::query()
                ->orderBy('sort')
                ->where('type', 2)
                ->where('status', 1)
                ->whereIn('id', $menuIds)
                ->pluck('permission');
            $user->permissions = $permissions;
        }
        return $this->success(new AdminUserResource($user));
    }

    public function updatePassword(Request $request)
    {
        $params = $this->validate($request, [
            'password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user = auth('admin')->user();

        if (!Hash::check($params['password'], $user->password)) {
            return $this->failed('旧密码错误');
        }
        $user->password = $params['new_password'];
        $user->save();

        return $this->message('操作成功');
    }

    public function logout()
    {
        auth('admin')->logout();

        return $this->message('操作成功');
    }
}
