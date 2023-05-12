<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $pid = $request->input('parent_id', 0);
        $list = Region::query()
            ->where('parent_id', $pid)
            ->get();
        return $this->success($list);
    }
}
