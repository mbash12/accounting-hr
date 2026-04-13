<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Builder;

class InvoiceSyncJob extends Model
{
    use HasFactory, Prunable;

    protected $table = 'invoice_sync_jobs';

    protected $fillable = [
        'sync_type',
        'status',
        'sales_invoice_id',
        'company_id',
        'event',
        'payload',
        'result',
        'error_message',
        'retry_count',
        'max_retries',
        'debug_logs',
        'started_at',
        'completed_at',
        // Snapshot fields for invoice data (to retain after deletion)
        'invoice_number',
        'job_number',
        'customer_name',
        'total_amount',
        'invoice_date',
    ];

    protected $casts = [
        'payload' => 'array',
        'result' => 'array',
        'debug_logs' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_RETRYING = 'retrying';

    /**
     * Get the Sales Invoice associated with this sync job.
     */
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    /**
     * Get the Company associated with this sync job.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Mark the job as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'started_at' => now(),
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    /**
     * Mark the job as completed.
     */
    public function markAsCompleted(array $result = []): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'result' => $result,
        ]);
    }

    /**
     * Mark the job as failed.
     */
    public function markAsFailed(string $error, array $debugLogs = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'completed_at' => now(),
            'error_message' => $error,
            'debug_logs' => array_merge($this->debug_logs ?? [], $debugLogs),
        ]);
    }

    /**
     * Mark for retry.
     */
    public function markForRetry(): void
    {
        $this->update([
            'status' => self::STATUS_RETRYING,
        ]);
    }

    /**
     * Check if the job can be retried.
     */
    public function canRetry(): bool
    {
        return $this->status === self::STATUS_FAILED 
            && $this->retry_count < $this->max_retries;
    }

    /**
     * Get execution time in human readable format.
     */
    public function getExecutionTime(): ?string
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        
        $seconds = $this->completed_at->diffInSeconds($this->started_at);
        if ($seconds < 60) {
            return "{$seconds}s";
        }
        
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        return "{$minutes}m {$remainingSeconds}s";
    }

    /**
     * Add a debug log entry.
     */
    public function addDebugLog(string $type, string $message, array $context = []): void
    {
        $logs = $this->debug_logs ?? [];
        $logs[] = [
            'timestamp' => now()->toDateTimeString(),
            'type' => $type,
            'message' => $message,
            'context' => $context,
        ];
        $this->update(['debug_logs' => $logs]);
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PROCESSING => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_FAILED => 'red',
            self::STATUS_RETRYING => 'orange',
            default => 'gray',
        };
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('status', self::STATUS_COMPLETED)
            ->where('completed_at', '<=', now()->subDays(7));
    }

    /**
     * Scope: Pending jobs.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Failed jobs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope: Recent jobs.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get summary statistics.
     */
    public static function getStats(int $days = 7): array
    {
        $query = self::where('created_at', '>=', now()->subDays($days));
        
        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', self::STATUS_PENDING)->count(),
            'processing' => (clone $query)->where('status', self::STATUS_PROCESSING)->count(),
            'completed' => (clone $query)->where('status', self::STATUS_COMPLETED)->count(),
            'failed' => (clone $query)->where('status', self::STATUS_FAILED)->count(),
            'retrying' => (clone $query)->where('status', self::STATUS_RETRYING)->count(),
        ];
    }
}
