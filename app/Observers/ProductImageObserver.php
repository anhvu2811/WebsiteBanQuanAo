<?php

namespace App\Observers;

use App\Models\ProductImage;
use App\Models\AuditLog;

class ProductImageObserver
{
    private $model = ProductImage::class;
    public function created(ProductImage $productImage): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('created', $this->model, $productImage->id, $productImage->getAttributes());
    }

    public function updated(ProductImage $productImage): void
    {
        //
    }
    public function deleted(ProductImage $productImage): void
    {
        //
    }
    public function restored(ProductImage $productImage): void
    {
        //
    }
    public function forceDeleted(ProductImage $productImage): void
    {
        //
    }
}
