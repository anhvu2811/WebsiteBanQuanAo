<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'tbl_product';
    protected $fillable =  [
        'id',
        'product_name',
        'product_description',
        'price',
        'quatity',
        'image_url',
        'category_id',
    ];

    public function category() {
        return $this>belongsTo(Category::class, 'category_id', 'id');
    }
}
