<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'schedule_id',
        'patient_name',
        'appointment_date',
        'appointment_time',
        'symptoms',
        'request_type',
        'appointment_id',
        'requested_changes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'requested_changes' => 'array',
        'reviewed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function getRequestTypeTextAttribute(): string
    {
        return match($this->request_type) {
            'create' => 'Buat Baru',
            'edit' => 'Edit Appointment',
            default => 'Unknown'
        };
    }
}
