<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKeys;

class ProductObserver
{
    private $model = Product::class;
    
    public function created(Product $product): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('created', $this->model, $product->id, $product->getAttributes());
        Cache::forget(CacheKeys::productDetail($id));
    }
    public function updated(Product $product): void
    {
        $data = [];
        foreach($product->getChanges() as $key => $newValue) {
            if(in_array($key, ['slug', 'updated_at', 'created_at'])) {
                continue;
            }
            $oldValue = $product->getOriginal($key);
            $data[$key] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }
        $auditLog = new AuditLog();
        $auditLog->auditLogs('updated', $this->model, $product->id, $data);
        Cache::forget(CacheKeys::productDetail($id));
    }
    public function deleted(Product $product): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('deleted', $this->model, $product->id, $product->getOriginal());
        Cache::forget(CacheKeys::productDetail($id));
    }
    public function restored(Product $product): void
    {
        //
    }
    public function forceDeleted(Product $product): void
    {
        //
    }
}
