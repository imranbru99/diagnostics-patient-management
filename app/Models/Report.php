<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'ai_critical_flags' => 'array',
        'delivery_channels' => 'array',
        'ai_analyzed_at' => 'datetime',
        'verified_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $year = date('Y');
            if (empty($report->report_number)) {
                $count = static::whereYear('created_at', $year)->count() + 1;
                $report->report_number = sprintf('RPT-%s-%05d', $year, $count);
            }

            if (empty($report->slug)) {
                $report->slug = 'rep-' . Str::lower(Str::random(10));
            }

            if (empty($report->verification_hash)) {
                $report->verification_hash = hash('sha256', $report->report_number . '-' . Str::random(16) . '-' . time());
            }

            if (empty($report->qr_code_url)) {
                $report->qr_code_url = url('/verify-report/' . $report->verification_hash);
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function pathologist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pathologist_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ReportValue::class);
    }

    public function hasAbnormalValues(): bool
    {
        return $this->values()->whereIn('flag', ['low', 'high', 'critical_low', 'critical_high'])->exists();
    }

    public function hasCriticalValues(): bool
    {
        return $this->values()->whereIn('flag', ['critical_low', 'critical_high'])->exists();
    }
}
