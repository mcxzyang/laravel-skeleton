<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $list = Product::filter($request->all())
            ->with(['productSkus'])
            ->orderBy('id', 'desc')
            ->paginate($request->get('pageSize', config('app.pageSize')));
        return $this->success($list);
    }

    public function show(Product $product)
    {
        return $this->success($product->load(['productSkus']));
    }

    protected function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
            'product_number' => 'required',
            'vendor_id' => 'required'
        ]);

        $params = $request->only(['title', 'image', 'content', 'product_number', 'product_group_id', 'category_id', 'provider', 'is_free_post', 'product_skus', 'companies', 'vendor_id']);

        $product->fill($params);
        $product->save();

        if (isset($params['product_skus']) && count($params['product_skus'])) {
            foreach ($params['product_skus'] as $item) {
                $productSku = new ProductSku(['product_id' => $product->id]);
                $productSku->fill($item);
                $productSku->save();
            }
        }
        $product->companyMap()->sync($params['companies'] ?? []);

        return $this->message('操作成功');
    }

    public function update(Request $request, Product $product)
    {
        $params = $request->only(['title', 'image', 'content', 'product_number', 'product_group_id', 'category_id', 'provider', 'is_free_post', 'product_skus', 'companies', 'status', 'vendor_id']);

        $product->fill($params);
        $product->save();

        $productSkus = [];
        if (isset($params['product_skus']) && count($params['product_skus'])) {
            foreach ($params['product_skus'] as $item) {
                $productSku = new ProductSku(['product_id' => $product->id]);
                if (isset($item['id']) && $item['id']) {
                    $productSku = ProductSku::query()->where(['product_id' => $product->id, 'id' => $item['id']])->first();
                }
                $productSku->fill($item);
                $productSku->save();

                $productSkus[] = $productSku->id;
            }
        }
        ProductSku::query()->where(['product_id' => $product->id])->whereNotIn('id', $productSkus)->delete();
        $product->companyMap()->sync($params['companies'] ?? []);

        return $this->message('操作成功');
    }

    public function destroy(Product $product)
    {
        $product->productSkus()->delete();
        $product->delete();

        return $this->message('删除成功');
    }
}
