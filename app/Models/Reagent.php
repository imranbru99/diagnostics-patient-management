<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reagent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'stock_quantity' => 'decimal:2',
        'min_alert_threshold' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'expiry_date' => 'date',
        'is_expired' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function consumptions(): HasMany
    {
        return $this->hasMany(ReagentConsumption::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_alert_threshold;
    }
}
