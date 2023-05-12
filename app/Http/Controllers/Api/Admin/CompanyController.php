<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Cache;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $paging = $request->input('paging', 1);
        $query = Company::query()->orderBy('id', 'desc');
        $list = $paging ? $query->paginate($request->get('pageSize', config('app.pageSize'))) : $query->get();
        return $this->success($list);
    }

    public function store(Request $request, Company $company)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $params = $request->only(['name', 'link_name', 'link_phone', 'client_id', 'client_secret']);
        $company->fill($params);
        $company->save();

        Cache::forget('key');

        return $this->message('操作成功');
    }

    public function update(Request $request, Company $company)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $params = $request->only(['name', 'link_name', 'link_phone', 'client_id', 'client_secret']);
        $company->fill($params);
        $company->save();

        Cache::forget('key');

        return $this->message('操作成功');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        Cache::forget('key');

        return $this->message('删除成功');
    }
}
