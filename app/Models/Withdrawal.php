<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'withdrawal_number',
        'amount',
        'payment_method',
        'account_number',
        'account_name',
        'bank_name',
        'branch_name',
        'user_note',
        'admin_note',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'approved' => 'bg-success-subtle text-success border-success',
            'completed' => 'bg-primary-subtle text-primary border-primary',
            'pending' => 'bg-warning-subtle text-warning border-warning',
            'rejected' => 'bg-danger-subtle text-danger border-danger',
            'cancelled' => 'bg-secondary-subtle text-secondary border-secondary',
            default => 'bg-light text-dark border-secondary',
        };
    }
}
