<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'investment_post_id',
        'total_profit_distributed',
        'period_label',
        'distributed_at',
    ];

    protected $casts = [
        'total_profit_distributed' => 'decimal:2',
        'distributed_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(InvestmentPost::class, 'investment_post_id');
    }
}
