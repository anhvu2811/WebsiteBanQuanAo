<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $table = 'tbl_audit_logs';
    protected $fillable = [
        'user_id',
        'event',
        'model_type',
        'model_id',
        'data',
        'ip_address',
        'user_agent',
    ];

    public function auditLogs($event, $modelType, $productId, $data)
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'event'       => $event,
            'model_type'  => $modelType,
            'model_id'    => $productId,
            'data'     => json_encode($data),
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}

