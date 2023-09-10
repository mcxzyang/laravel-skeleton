<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminRoleResource;
use App\Models\AdminRole;
use App\Models\AdminRoleMenus;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    public function index(Request $request)
    {
        $paging = $request->input('paging', 1);
        $query = AdminRole::filter($request->all())->orderBy('id', 'desc');
        $list = $paging ? $query->paginate($request->get('size', config('app.size'))) : $query->get();
        return $this->success(AdminRoleResource::collection($list));
    }

    public function store(Request $request, AdminRole $adminRole)
    {
        $params = $request->only(['name', 'code', 'menu_ids', 'description']);

        $adminRole->fill($params);
        $adminRole->save();

        if (isset($params['menu_ids']) && count($params['menu_ids'])) {
            $adminRole->menus()->sync($params['menu_ids']);
        }

        return $this->message('操作成功');
    }

    public function show(AdminRole $adminRole)
    {
        return $this->success(new AdminRoleResource($adminRole));
    }

    public function update(Request $request, AdminRole $adminRole)
    {
        $params = $request->only(['name', 'code', 'menu_ids', 'description']);

        $adminRole->fill($params);
        $adminRole->save();

        if (isset($params['menu_ids'])) {
            $adminRole->menus()->sync($params['menu_ids'] ?: []);
        }

        return $this->message('操作成功');
    }

    public function destroy(AdminRole $adminRole)
    {
        AdminRoleMenus::query()->where('role_id', $adminRole->id)->delete();
        $adminRole->delete();

        return $this->message('操作成功');
    }
}
