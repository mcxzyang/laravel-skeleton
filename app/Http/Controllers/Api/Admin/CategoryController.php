<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $list = Category::filter($request->all())
            ->where(['pid' => 0])
            ->orderBy('id')
            ->get();
        $list = CategoryResource::collection($list);
        return $this->success($list);
    }

    public function list()
    {
        $list = Category::query()
            ->where(['pid' => 0])
            ->orderBy('id')
            ->get();
        return $this->success($list);
    }

    public function store(Request $request, Category $category)
    {
        $params = $request->only(['name', 'pid', 'image']);

        $category->fill($params);
        $category->save();

        return $this->message('操作成功');
    }

    public function update(Request $request, Category $category)
    {
        $params = $request->only(['name', 'pid', 'image']);

        $category->fill($params);
        $category->save();

        return $this->message('操作成功');
    }

    public function destroy(Category $category)
    {
        Category::query()->where(['pid' => $category->id])->delete();
        $category->delete();

        return $this->message('删除成功');
    }
}
