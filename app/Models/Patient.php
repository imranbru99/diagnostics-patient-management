<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_birth' => 'date',
        'age_years' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->mrn)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $patient->mrn = sprintf('MRN-%s-%05d', $year, $count);
            }

            if (empty($patient->slug)) {
                // Universal slug compatible: converts unicode/bangla names to clean unique slugs
                $baseSlug = Str::slug($patient->name);
                if (empty($baseSlug)) {
                    $baseSlug = 'patient-' . Str::lower(Str::random(6));
                }
                $patient->slug = $baseSlug . '-' . Str::lower(Str::random(4));
            }

            if (empty($patient->portal_pin)) {
                $patient->portal_pin = sprintf('%04d', rand(1000, 9999));
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(PatientFamily::class, 'patient_id');
    }

    public function linkedByFamilies(): HasMany
    {
        return $this->hasMany(PatientFamily::class, 'relative_patient_id');
    }

    public function getFormattedAgeAttribute(): string
    {
        if ($this->age_years) {
            return "{$this->age_years} Y";
        }
        if ($this->date_of_birth) {
            return $this->date_of_birth->age . " Y";
        }
        return 'N/A';
    }
}
