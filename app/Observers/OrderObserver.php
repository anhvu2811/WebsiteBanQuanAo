<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\AuditLog;

class OrderObserver
{
    private $model = Order::class;

    public function created(Order $order): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('created', $this->model, $order->id, $order->getAttributes());
    }

    public function updated(Order $order): void
    {
        $data = [];
        foreach($order->getChanges() as $key => $newValue) {
            if(in_array($key, ['slug', 'updated_at', 'created_at'])) {
                continue;
            }
            $oldValue = $order->getOriginal($key);
            $data[$key] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }
        $auditLog = new AuditLog();
        $auditLog->auditLogs('updated', $this->model, $order->id, $data);
    }

    public function deleted(Order $order): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('deleted', $this->model, $order->id, $order->getOriginal());
    }
}
