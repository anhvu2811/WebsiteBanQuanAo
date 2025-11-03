<?php

namespace App\Observers;

use App\Models\ProductSize;

class ProductSizeObserver
{
    /**
     * Handle the ProductSize "created" event.
     */
    private $model = ProductSize::class;

    public function created(ProductSize $productSize): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('created', $this->model, $productSize->id, $productSize->getAttributes());
    }

    /**
     * Handle the ProductSize "updated" event.
     */
    public function updated(ProductSize $productSize): void
    {
        //
    }

    /**
     * Handle the ProductSize "deleted" event.
     */
    public function deleted(ProductSize $productSize): void
    {
        //
    }

    /**
     * Handle the ProductSize "restored" event.
     */
    public function restored(ProductSize $productSize): void
    {
        //
    }

    /**
     * Handle the ProductSize "force deleted" event.
     */
    public function forceDeleted(ProductSize $productSize): void
    {
        //
    }
}
