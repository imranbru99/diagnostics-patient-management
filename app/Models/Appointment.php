<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'appointment_date' => 'date',
        'is_walk_in' => 'boolean',
        'checked_in_at' => 'datetime',
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($apt) {
            $year = date('Y');
            if (empty($apt->appointment_number)) {
                $count = static::whereYear('created_at', $year)->count() + 1;
                $apt->appointment_number = sprintf('APT-%s-%05d', $year, $count);
            }

            if (empty($apt->token_number)) {
                // Generate daily sequence token, e.g. T-101
                $todayCount = static::whereDate('appointment_date', $apt->appointment_date ?? today())->count() + 1;
                $prefix = $apt->is_walk_in ? 'W' : 'A';
                $apt->token_number = sprintf('%s-%03d', $prefix, $todayCount);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function testPackage(): BelongsTo
    {
        return $this->belongsTo(TestPackage::class);
    }
}
