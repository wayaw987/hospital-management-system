<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_number',
        'medical_history',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'reviewed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
