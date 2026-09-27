<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportValue extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'value_numeric' => 'float',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(TestParameter::class, 'test_parameter_id');
    }

    public function isNormal(): bool
    {
        return $this->flag === 'normal';
    }

    public function isCritical(): bool
    {
        return in_array($this->flag, ['critical_low', 'critical_high']);
    }
}
