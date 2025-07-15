<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientEditRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'requested_by',
        'requested_changes',
        'reason',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'requested_changes' => 'array',
        'reviewed_at' => 'datetime'
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }
}
