<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestReferenceRange extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'min_value' => 'float',
        'max_value' => 'float',
        'critical_low' => 'float',
        'critical_high' => 'float',
        'age_min_days' => 'integer',
        'age_max_days' => 'integer',
    ];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(TestParameter::class, 'test_parameter_id');
    }

    /**
     * Compute flag for a numeric value.
     */
    public function evaluateFlag(float $val): string
    {
        if ($this->critical_low !== null && $val <= $this->critical_low) {
            return 'critical_low';
        }
        if ($this->critical_high !== null && $val >= $this->critical_high) {
            return 'critical_high';
        }
        if ($this->min_value !== null && $val < $this->min_value) {
            return 'low';
        }
        if ($this->max_value !== null && $val > $this->max_value) {
            return 'high';
        }

        return 'normal';
    }
}
