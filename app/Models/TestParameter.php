<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestParameter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'sort_order' => 'integer',
        'is_mandatory' => 'boolean',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function referenceRanges(): HasMany
    {
        return $this->hasMany(TestReferenceRange::class);
    }

    /**
     * Resolve the matched reference range based on patient gender and age (in years).
     */
    public function getMatchedReferenceRange(string $gender = 'all', int $ageYears = 30): ?TestReferenceRange
    {
        $ageDays = $ageYears * 365;

        // Try exact gender match first
        $match = $this->referenceRanges()
            ->where('gender', strtolower($gender))
            ->where('age_min_days', '<=', $ageDays)
            ->where('age_max_days', '>=', $ageDays)
            ->first();

        if ($match) {
            return $match;
        }

        // Fallback to 'all' gender
        return $this->referenceRanges()
            ->where('gender', 'all')
            ->where('age_min_days', '<=', $ageDays)
            ->where('age_max_days', '>=', $ageDays)
            ->first() ?? $this->referenceRanges()->first();
    }
}
