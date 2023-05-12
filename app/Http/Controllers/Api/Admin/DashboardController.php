<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function contentData()
    {
        $data = [
            [
                'x' => '2023-02-13',
                'y' => 58
            ],
            [
                'x' => '2023-02-14',
                'y' => 81
            ],
            [
                'x' => '2023-02-15',
                'y' => 43
            ],
            [
                'x' => '2023-02-16',
                'y' => 63
            ],
            [
                'x' => '2023-02-17',
                'y' => 90
            ],
            [
                'x' => '2023-02-18',
                'y' => 49
            ],
            [
                'x' => '2023-02-19',
                'y' => 79
            ],
            [
                'x' => '2023-02-20',
                'y' => 64
            ]
        ];
        return $this->success($data);
    }

    public function popular()
    {
        $data = [
            [
                'key' => 1,
                'clickNumber' => '346.3w+',
                'title' => '经济日报：财政政策要精准提升…',
                'increase' => 35
            ],
            [
                'key' => 2,
                'clickNumber' => '346.3w+',
                'title' => '经济日报：财政政策要精准提升…',
                'increase' => 35
            ],
            [
                'key' => 3,
                'clickNumber' => '346.3w+',
                'title' => '经济日报：财政政策要精准提升…',
                'increase' => 35
            ],
            [
                'key' => 4,
                'clickNumber' => '346.3w+',
                'title' => '经济日报：财政政策要精准提升…',
                'increase' => 35
            ]
        ];
        return $this->success($data);
    }
}
