<?php
/**
 * User: cherrybeal
 * Date: 2020/9/3
 * Time: 2:40 下午
 */

namespace App\Services;

use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Redis;

class SmsSendService
{
    public function sendCodeFormat($mobile, $prefix, $templateId)
    {
        $config = config('sms');
        $easySms = new EasySms($config);
        $code = rand(100000, 999999);
        try {
            $result = $easySms->send($mobile, [
                'template' => $templateId,
                'data' => [
                    'code' => $code
                ],
            ]);
            if ($result && $result['aliyun']['result']['Code'] === 'OK') {
                Redis::setex($prefix . $mobile, 600, $code);

                return true;
            } else {
                $message = $result['aliyun']['result']['Message'];
                \Log::info("短信发送失败 - {$mobile} - {$message}");
                return false;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
//            $message = $error->getMessage();
            \Log::info("短信发送失败 - {$mobile} - {$error}");
            return false;
        }
    }
}
