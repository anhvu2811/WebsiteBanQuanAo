<?php
namespace App\Support;

class CacheKeys 
{
    public static function productDetail(int $id): string
    {
        return "product_detail_{$id}";
    }

    public static function productCollection(): string
    {
        return "product_collection";
    }

    public static function setting(): string
    {
        return "setting";
    }
}