<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'gallery_images',
        'total_quantity',
        'unit_cost',
        'profit_per_unit',
        'expected_import_days',
        'target_amount',
        'current_invested_amount',
        'min_investment_amount',
        'status',
        'type',
        'msg_profit_payment',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'unit_cost' => 'decimal:2',
        'profit_per_unit' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'current_invested_amount' => 'decimal:2',
        'min_investment_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function profitDistributions(): HasMany
    {
        return $this->hasMany(ProfitDistribution::class);
    }

    public function getFundedPercentageAttribute(): int
    {
        if ($this->target_amount <= 0) return 0;
        return (int) min(100, round(($this->current_invested_amount / $this->target_amount) * 100));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_invested_amount);
    }

    public function getTimeLabelAttribute(): string
    {
        return match($this->type) {
            'Local' => 'Local Time',
            'Manufacture' => 'Manufacture Time',
            default => 'Import Time',
        };
    }

    public function getGalleryImageUrlsAttribute(): array
    {
        $urls = [];
        $images = is_array($this->gallery_images) ? $this->gallery_images : [];

        if ($this->image) {
            $urls[] = asset($this->image);
        }

        foreach ($images as $img) {
            if ($img && $img !== $this->image) {
                $urls[] = asset($img);
            }
        }

        if (empty($urls)) {
            $urls[] = asset('asset/images/earbuds.jpg');
        }

        return array_unique($urls);
    }
}
