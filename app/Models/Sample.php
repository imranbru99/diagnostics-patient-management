<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sample extends Model
{
    use HasFactory;

    const STATUS_COLLECTED = 'collected';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_RECEIVED_AT_LAB = 'received_at_lab';
    const STATUS_IN_TESTING = 'in_testing';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    protected $guarded = ['id'];

    protected $casts = [
        'collected_at' => 'datetime',
        'received_at' => 'datetime',
        'verified_at' => 'datetime',
        'recollection_requested_at' => 'datetime',
        'recollection_required' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sample) {
            if (empty($sample->barcode)) {
                $sample->barcode = 'SMP-' . date('Y') . '-' . strtoupper(Str::random(6));
            }
            if (empty($sample->collected_at)) {
                $sample->collected_at = now();
            }
        });
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

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function custodyLogs(): HasMany
    {
        return $this->hasMany(SampleCustodyLog::class)->orderByDesc('logged_at');
    }

    /**
     * Transition state and log chain of custody
     */
    public function transitionTo(string $newStatus, ?int $userId = null, ?string $notes = null, ?string $from = null, ?string $to = null): void
    {
        $this->update(['status' => $newStatus]);

        $this->custodyLogs()->create([
            'user_id' => $userId ?? auth()->id(),
            'action' => 'Transitioned to ' . str_replace('_', ' ', strtoupper($newStatus)),
            'from_location' => $from,
            'to_location' => $to,
            'notes' => $notes,
            'logged_at' => now(),
        ]);
    }
}
