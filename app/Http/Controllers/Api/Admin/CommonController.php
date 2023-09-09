<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function captcha()
    {
        return $this->success(app('captcha')->create('flat', true));
    }
}
