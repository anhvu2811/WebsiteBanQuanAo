<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'tbl_order';
    protected $fillable = [
        'id',
        'order_date',
        'customer_id',
        'total_price',
        'coupon_code',
        'payment_method',
        'payment_status',
        'shipping_address',
        'notes',
    ];

    const PENDING = 'Pending';
    const COMLETED = 'Completed';
    const CANCELED = 'Canceled';
    const SHIPPING = 'Shipping';

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }
    public function user() {
        return $this->belongsTo(User::class, 'customer_id');
    }


}
