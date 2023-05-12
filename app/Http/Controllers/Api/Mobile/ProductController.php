<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCompanyMapping;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();
        $productIds = ProductCompanyMapping::query()->where(['company_id' => $company->id])->pluck('product_id');

        $list = Product::filter($request->all())
            ->with(['productSkus', 'productGroup', 'category'])
            ->where(['status' => 1])
            // ->whereIn('id', $productIds)
            ->orderBy('id', 'desc')
            ->paginate($request->get('pageSize', config('app.pageSize')));

        return $this->success($list);
    }

    public function show(Request $request, Product $product)
    {
        $xApiKey = $request->header(config('app.apiKeyName'));
        $company = Company::query()->where(['key' => $xApiKey])->first();
        $mapping = ProductCompanyMapping::query()->where(['company_id' => $company->id, 'product_id' => $product->id])->first();
        if (!$mapping) {
            return $this->failed('商品不存在');
        }
        return $this->success($product->load(['productSkus','productGroup', 'category', 'orderItemRates']));
    }
}
