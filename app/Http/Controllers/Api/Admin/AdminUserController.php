<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\AdminUserExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserResource;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $paging = $request->input('paging', 1);
        $query = AdminUser::filter($request->all())->orderBy('id', 'desc');
        $list = $paging ? $query->paginate($request->get('size', config('app.size'))) : $query->get();
        return $this->success(AdminUserResource::collection($list));
    }

    public function show(AdminUser $adminUser)
    {
        return $this->success((new AdminUserResource($adminUser))->hide(['updated_at']));
    }

    public function store(Request $request, AdminUser $adminUser)
    {
        $this->validate($request, [
            'username' => 'required|unique:admin_users,username',
            'name' => 'required',
        ]);
        $params = $request->only(['username', 'name', 'password', 'roles']);

        $adminUser->fill($params);
        $adminUser->save();

        if (isset($params['roles']) && count($params['roles'])) {
            $adminUser->adminRoles()->sync($params['roles']);
        }

        return $this->message('操作成功');
    }

    public function update(Request $request, AdminUser $adminUser)
    {
        $this->validate($request, [
            'username' => 'required',
            'name' => 'required',
        ]);
        $params = $request->only(['username', 'name', 'password', 'roles']);
        $adminUserResult = AdminUser::query()->where(['username' => $params['username']])->where(
            'id',
            '!=',
            $adminUser->id
        )->first();

        if ($adminUserResult) {
            return $this->failed('该用户名已存在');
        }
        $adminUser->fill($params);
        $adminUser->save();

        if (isset($params['roles'])) {
            $adminUser->adminRoles()->sync($params['roles'] ?: []);
        }

        return $this->message('操作成功');
    }

    public function destroy(AdminUser $adminUser)
    {
        $adminUser->delete();

        return $this->message('操作成功');
    }

    public function export(Request $request)
    {
        $query = AdminUser::filter($request->all());
        return Excel::download(
            new AdminUserExport($query),
            '管理员列表'.date('YmdHis').'.xlsx',
            null,
            ['Access-Control-Expose-Headers' => 'content-disposition']
        );
    }
}
