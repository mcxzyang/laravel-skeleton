<?php

namespace App\Models;

use App\ModelFilters\ProductFilter;
use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'title', 'image', 'product_number', 'product_group_id', 'category_id', 'provider', 'is_free_post', 'status', 'sales_number', 'content',
        'vendor_id', 'source'
    ];

    protected $appends = [
        'first_sku', 'companies'
    ];

    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }

//    protected $casts = [
//        'is_free_post' => 'boolean'
//    ];

    public function productGroup()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function getFirstSkuAttribute()
    {
        $productSkus = $this->productSkus();
        if ($productSkus && $productSkus->count() > 0) {
            return $productSkus->first();
        }
        return ['stock' => null, 'price' => null];
    }

    public function companyMap()
    {
        return $this->belongsToMany(Company::class, 'product_company_mappings', 'product_id', 'company_id');
    }

    public function getCompaniesAttribute()
    {
        return $this->companyMap()->pluck('companies.id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orderItemRates()
    {
        return $this->hasMany(OrderItemRate::class);
    }
}
