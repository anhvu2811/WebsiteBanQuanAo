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
        'user_id',
        'order_date',
        'total_amount',
        'status'
    ];
}
