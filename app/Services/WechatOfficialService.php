<?php
/**
 * This file is part of the promotion
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Services;

use App\Exceptions\ErrorMessageException;
use App\Exceptions\InvalidRequestException;
use GuzzleHttp\Client;

class WechatOfficialService
{
    public function getOpenId($code, $appid, $secret)
    {
        try {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $client = new Client();
            $response = $client->request('get', $url);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            throw new InvalidRequestException('获取 openid 失败');
        }
    }

    public function getUserInfo($accessToken, $openid)
    {
        try {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openid&lang=zh_CN";
            $client = new Client();
            $response = $client->request('get', $url);
            return json_decode($response->getBody()->getContents());
        } catch (\PDOException $e) {
            throw new InvalidRequestException('获取 用户信息 失败');
        }
    }
}
