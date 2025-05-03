<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'tbl_cart_item';
    protected $fillable =  [
        'id',
        'user_id',
        'product_id',
        'size_id',
        'quantity',
        'price'
    ];
    
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function size() {
        return $this->belongsTo(Size::class);
    }
}
