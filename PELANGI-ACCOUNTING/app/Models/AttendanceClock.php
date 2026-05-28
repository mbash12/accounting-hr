<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceClock extends Model
{
    public const TYPE_IN = 'in';

    public const TYPE_OUT = 'out';

    public const SOURCE_BIOMETRIC = 'biometric';

    public const SOURCE_APP = 'app';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_DINAS = 'dinas';

    protected $fillable = [
        'attendance_id',
        'type',
        'clocked_at',
        'source',
        'latitude',
        'longitude',
        'photo_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'clocked_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'attendance_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (AttendanceClock $clock) {
            if ($clock->attendance) {
                app(\App\Services\AttendanceClockService::class)->syncSummary($clock->attendance);
            }
        });

        static::deleted(function (AttendanceClock $clock) {
            $attendance = Attendance::find($clock->attendance_id);
            if ($attendance) {
                app(\App\Services\AttendanceClockService::class)->syncSummary($attendance);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public static function sourceOptions(): array
    {
        return [
            self::SOURCE_BIOMETRIC => __('Biometrik'),
            self::SOURCE_APP => __('Aplikasi'),
            self::SOURCE_MANUAL => __('Manual'),
            self::SOURCE_DINAS => __('Dinas'),
        ];
    }

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
}
