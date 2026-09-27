<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientFamily extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'can_view_reports' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function relativePatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'relative_patient_id');
    }
}
