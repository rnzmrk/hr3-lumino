<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an audit trail entry.
     */
    public static function log(string $action, string $subjectType, int $subjectId, string $description = null, array $oldValues = null, array $newValues = null)
    {
        $user = auth()->user();
        
        try {
            AuditLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'action' => $action,
                'subjectType' => $subjectType,
                'subjectId' => $subjectId
            ]);
        }
    }
    
    /**
     * Log creation of a model.
     */
    public static function created($model, string $description = null)
    {
        self::log(
            'created',
            get_class($model),
            $model->id,
            $description ?? "Created " . class_basename($model),
            null,
            $model->toArray()
        );
    }
    
    /**
     * Log update of a model.
     */
    public static function updated($model, array $oldValues, string $description = null)
    {
        self::log(
            'updated',
            get_class($model),
            $model->id,
            $description ?? "Updated " . class_basename($model),
            $oldValues,
            $model->toArray()
        );
    }
    
    /**
     * Log deletion of a model.
     */
    public static function deleted($model, string $description = null)
    {
        self::log(
            'deleted',
            get_class($model),
            $model->id,
            $description ?? "Deleted " . class_basename($model),
            $model->toArray(),
            null
        );
    }
    
    /**
     * Log approval action.
     */
    public static function approved($model, string $description = null)
    {
        self::log(
            'approved',
            get_class($model),
            $model->id,
            $description ?? "Approved " . class_basename($model),
            null,
            $model->toArray()
        );
    }
    
    /**
     * Log rejection action.
     */
    public static function rejected($model, string $description = null)
    {
        self::log(
            'rejected',
            get_class($model),
            $model->id,
            $description ?? "Rejected " . class_basename($model),
            null,
            $model->toArray()
        );
    }
}
