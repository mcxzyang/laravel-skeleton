<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $paging = $request->input('paging', 1);
        $query = Vendor::filter($request->all())->orderBy('id', 'desc');
        $list = $paging ? $query->paginate($request->get('pageSize', config('app.pageSize'))) : $query->get();
        return $this->success($list);
    }

    public function store(Request $request, Vendor $company)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $params = $request->only(['name', 'link_name', 'link_phone', 'wechat_no']);
        $company->fill($params);
        $company->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $params = $request->only(['name', 'link_name', 'link_phone', 'wechat_no']);
        $vendor->fill($params);
        $vendor->save();

        return $this->message('操作成功');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return $this->message('删除成功');
    }
}
