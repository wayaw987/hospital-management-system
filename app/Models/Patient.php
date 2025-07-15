<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'patient_number',
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'blood_type',
        'allergies',
        'medical_history',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_number',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->birth_date->age;
    }

    public function getGenderTextAttribute()
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    // Boot method untuk auto generate patient number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($patient) {
            if (empty($patient->patient_number)) {
                $patient->patient_number = 'P' . date('Ymd') . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
