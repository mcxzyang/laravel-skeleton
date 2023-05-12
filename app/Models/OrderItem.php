<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use FormatDate;

    protected $fillable = [
        'order_id', 'product_id', 'product_sku_id', 'price', 'stock', 'total', 'apply_refund_at', 'status',
        'after_status', 'express_no', 'express_company_name', 'delivery_time', 'receiving_time'
    ];

    public const STATUS_NORMAL = 1;
    public const STATUS_REFUNDING = 2;
    public const STATUS_REFUNDED = 3;

    public const AFTER_STATUS_UNDER_REVIEW = 1;
    public const AFTER_STATUS_UNDER_REFUND = 2;
    public const AFTER_STATUS_IN_RETURN = 3;
    public const AFTER_STATUS_SUCCESS_REFUND = 4;
    public const AFTER_STATUS_FAIL_REFUND = 5;
    public const AFTER_STATUS_FAIL_PASS = 6;
    public const AFTER_STATUS_IN_CHECK = 7;
    public const AFTER_STATUS_FINISH_RETURN = 8;
    public const AFTER_STATUS_CLOSED = 9;
    public const AFTER_STATUS_PASSED = 10;

    // public static $statusMap = [
    //     self::STATUS_NORMAL => '正常',
    //     self::STATUS_REFUNDING => '退款申请中',
    //     self::STATUS_REFUNDED => '退款完成'
    // ];

    public static $afterStatusMap = [
        self::AFTER_STATUS_UNDER_REVIEW => '审核中',
        self::AFTER_STATUS_UNDER_REFUND => '退款中',
        self::AFTER_STATUS_IN_RETURN => '退货中',
        self::AFTER_STATUS_SUCCESS_REFUND => '退款成功',
        self::AFTER_STATUS_FAIL_REFUND => '退款失败',
        self::AFTER_STATUS_FAIL_PASS => '审核不通过',
        self::AFTER_STATUS_IN_CHECK => '审核中',
        self::AFTER_STATUS_FINISH_RETURN => '退款完成，拒绝退款',
        self::AFTER_STATUS_CLOSED => '已关闭',
        self::AFTER_STATUS_PASSED => '审核通过'
    ];

    protected $appends = [
        'status_text'
    ];

    public function getStatusTextAttribute()
    {
        if ($this->status !== null) {
            return Order::$statusMap[$this->status];
        }
        return null;
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
