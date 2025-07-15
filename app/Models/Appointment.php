<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'appointment_time',
        'status',
        'symptoms',
        'diagnosis',
        'prescription',
        'notes',
        'consultation_fee',
        'is_paid'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'consultation_fee' => 'decimal:2',
        'is_paid' => 'boolean'
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('appointment_date', $date);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            'scheduled' => 'Terjadwal',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getFormattedTimeAttribute()
    {
        return date('H:i', strtotime($this->appointment_time)) . ' WIB';
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->setTimezone('Asia/Jakarta')->format('d/m/Y');
    }

    public function getFullDateTimeAttribute()
    {
        return $this->getFormattedDateAttribute() . ' ' . $this->getFormattedTimeAttribute();
    }

    // Boot method untuk auto generate appointment number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $latest = Appointment::whereDate('created_at', today())->count();
                $appointment->appointment_number = 'APT-' . date('Y') . '-' . str_pad($latest + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
