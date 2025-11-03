<?php

namespace App\Observers;

use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKeys;

class SettingObserver
{
    private $model = Setting::class;
    public function created(Setting $setting): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('created', $this->model, $setting->id, $setting->getAttributes());

        Cache::forget(CacheKeys::setting());
    }

    public function updated(Setting $setting): void
    {
        $data = [];
        foreach($setting->getChanges() as $key => $newValue) {
            if(in_array($key, ['updated_at', 'created_at'])) {
                continue;
            }
            $oldValue = $setting->getOriginal($key);
            $data[$key] = [
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }
        $auditLog = new AuditLog();
        $auditLog->auditLogs('updated', $this->model, $setting->id, $data);

        Cache::forget(CacheKeys::setting());
    }

    public function deleted(Setting $setting): void
    {
        $auditLog = new AuditLog();
        $auditLog->auditLogs('deleted', $this->model, $setting->id, $setting->getOriginal());

        Cache::forget(CacheKeys::setting());
    }
}
