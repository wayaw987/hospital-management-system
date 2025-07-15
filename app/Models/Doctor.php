<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialist',
        'address',
        'birth_date',
        'gender',
        'license_number',
        'experience_years',
        'consultation_fee',
        'education',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'consultation_fee' => 'decimal:2',
        'experience_years' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySpecialist($query, $specialist)
    {
        return $query->where('specialist', $specialist);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "Dr. {$this->name}";
    }
}
