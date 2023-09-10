<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AdminRoleMenu;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    /**
     * index
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = AdminMenu::query()
            ->with(['children'])->where('type', 1);
        $title = $request->input('title');
        $status = $request->input('status');
        if ($title) {
            $query->where('title', 'like', sprintf('%%%s%%', $title));
        }
        if ($status !== null) {
            $query->where('status', $status);
        }
        if (!$title && $status === null || $status == 1) {
            $query->where('pid', 0);
        }
        $list = $query
            ->orderBy('sort')
            ->get();
        return $this->success($list);
    }

    /**
     * tree select
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tree()
    {
        $list = AdminMenu::query()
            ->orderBy('sort')
            ->get();
        return $this->success($this->getTree($list));
    }

    public function route()
    {
        $adminUser = auth('admin')->user();

        $query = AdminMenu::query()
            ->orderBy('sort')
            ->where('status', 1)
            ->where('type', 1);
        if (!$adminUser->is_super_admin) {
            $menuIds = AdminRoleMenu::query()->whereIn('role_id', $adminUser->roles)->pluck('menu_id');
            $query->whereIn('id', $menuIds);
        }
        $list = $query->get();

        return $this->success($this->getRouteTree($list));
    }

    private function getRouteTree($data, $pId = 0)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {
                $v['children'] = $this->getRouteTree($data, $v['id']);
                $tree[] = [
                    'name' => $v['name'], 'path' => $v['path'], 'children' => $v['children'], 'meta' => [
                        'locale' => $v['title'],
                        'hideInMenu' => $v['hideInMenu'],
                        'icon' => $v['icon'],
                        'ignoreCache' => $v['ignoreCache'],
                        'order' => $v['sort'],
                        'requiresAuth' => true
                    ]
                ];
            }
        }
        return $tree;
    }

    private function getTree($data, $pId = 0)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {
                $v['children'] = $this->getTree($data, $v['id']);
                $tree[] = ['key' => $v['id'], 'title' => $v['title'], 'children' => $v['children']];
            }
        }
        return $tree;
    }

    public function store(Request $request, AdminMenu $adminMenu)
    {
        $params = $request->all();

        $adminMenu->fill($params);
        $adminMenu->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, AdminMenu $adminMenu)
    {
        $params = $request->all();

        $adminMenu->fill($params);
        $adminMenu->save();

        return $this->message('操作成功');
    }

    public function destroy(AdminMenu $adminMenu)
    {
        $adminMenu->children()->delete();
        $adminMenu->delete();

        return $this->message('删除成功');
    }
}
