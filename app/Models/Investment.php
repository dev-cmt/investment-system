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
        'investment_amount',
        'paid_amount',
        'due_amount',
        'calculated_quantity_share',
        'per_piece_profit',
        'expected_profit',
        'status',
    ];

    protected $casts = [
        'investment_amount'         => 'decimal:2',
        'paid_amount'               => 'decimal:2',
        'due_amount'                => 'decimal:2',
        'calculated_quantity_share' => 'integer',
        'per_piece_profit'          => 'decimal:2',
        'expected_profit'           => 'decimal:2',
    ];

    /**
     * Backward-compatibility accessor for amount.
     */
    public function getAmountAttribute(): float
    {
        return (float) ($this->attributes['investment_amount'] ?? 0);
    }

    /**
     * Backward-compatibility mutator for amount.
     */
    public function setAmountAttribute($value): void
    {
        $this->attributes['investment_amount'] = $value;
    }

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

    /**
     * Recalculate paid_amount and due_amount from approved payment transactions.
     */
    public function recalculatePaidAndDue(): void
    {
        $approvedPaid = (float) $this->payments()->where('status', 'approved')->sum('paid_amount');
        $investAmount = (float) ($this->investment_amount ?? 0);
        $due = max(0, $investAmount - $approvedPaid);

        $this->updateQuietly([
            'paid_amount' => $approvedPaid,
            'due_amount'  => $due,
        ]);
    }

    /**
     * Get per piece profit (falls back to post profit_per_unit if not set)
     */
    public function getPerPieceProfitAttribute($value): float
    {
        if ($value !== null && (float) $value > 0) {
            return (float) $value;
        }
        return (float) ($this->post->profit_per_unit ?? 0);
    }

    /**
     * Get expected profit: calculated_quantity_share * per_piece_profit
     */
    public function getExpectedProfitAttribute($value): float
    {
        $qty = (int) ($this->calculated_quantity_share ?? 0);
        $profitPerPiece = (float) $this->per_piece_profit;

        if ($qty > 0 && $profitPerPiece > 0) {
            return (float) ($qty * $profitPerPiece);
        }

        return (float) ($value ?? 0);
    }
}
