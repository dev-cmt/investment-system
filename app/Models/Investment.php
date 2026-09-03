<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_post_id',
        'amount',
        'calculated_quantity_share',
        'per_piece_profit',
        'expected_profit',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'per_piece_profit' => 'decimal:2',
        'expected_profit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(InvestmentPost::class, 'investment_post_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'investment_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'investment_id')->latestOfMany();
    }
}
