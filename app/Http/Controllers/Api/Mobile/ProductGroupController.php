<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\ProductGroup;
use Illuminate\Http\Request;

class ProductGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductGroup::filter($request->all())
            ->orderBy('id', 'asc');
        $paginate = $request->input('paginate', 1);
        $list = $paginate ? $query->paginate($request->get('pageSize', config('app.pageSize'))) : $query->get();
        return $this->success($list);
    }
}
