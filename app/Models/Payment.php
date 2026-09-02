<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'investment_id',
        'investment_post_id',
        'payment_method_id',
        'payment_reason_id',
        'ref_reason_id',
        'payment_number',
        'transaction_number',
        'transaction_id',
        'transfer_number',
        'paid_amount',
        'payment_date',
        'message',
        'slip',
        'status',
    ];

    protected $casts = [
        'paid_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(InvestmentPost::class, 'investment_post_id');
    }
}
