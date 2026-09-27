<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'role',
        'designation',
        'digital_signature',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function verifiedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'pathologist_id');
    }

    public function collectedSamples(): HasMany
    {
        return $this->hasMany(Sample::class, 'collected_by');
    }

    public function sampleCustodyLogs(): HasMany
    {
        return $this->hasMany(SampleCustodyLog::class);
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function isPathologist(): bool
    {
        return $this->role === 'pathologist';
    }

    public function isRadiologist(): bool
    {
        return $this->role === 'radiologist';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
