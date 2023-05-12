<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductGroup;
use Illuminate\Http\Request;

class ProductGroupController extends Controller
{
    public function index(Request $request)
    {
        $paging = $request->input('paging', 1);
        $query = ProductGroup::filter($request->all())->orderBy('id', 'desc');
        $list = $paging ? $query->paginate($request->get('pageSize', config('app.pageSize'))) : $query->get();
        return $this->success($list);
    }

    public function store(Request $request, ProductGroup $productGroup)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_groups'
        ]);

        $params = $request->only(['name', 'image']);

        $productGroup->fill($params);
        $productGroup->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, ProductGroup $productGroup)
    {
        $params = $request->only(['name', 'image']);

        $productGroup->fill($params);
        $productGroup->save();

        return $this->message('操作成功');
    }

    public function destroy(ProductGroup $productGroup)
    {
        $productGroup->delete();

        return $this->message('删除成功');
    }
}
