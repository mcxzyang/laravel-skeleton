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
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function testLogin()
    {
        $user = User::query()->find(1);
        $token = auth('mobile')->login($user);
        return $this->success($this->respondWithToken($token));
    }

    public function login(Request $request)
    {
        $params = $this->validate($request, [
            'clientId' => 'required',
            'userId' => 'required',
            'sign' => 'required'
        ]);
        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();

        if ($params['sign'] !== $this->generateSign($company, $params['userId'])) {
            return $this->failed('签名校验失败');
        }
        $user = User::query()->firstOrCreate([
            'company_id' => $company->id,
            'third_party_user_id' => $params['userId']
        ]);
        $token = auth('mobile')->login($user);
        return $this->success($this->respondWithToken($token));
    }

    private function generateSign($company, $userId)
    {
        return md5("{$company->client_id}{$company->client_secret}{$userId}");
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];
    }
}
