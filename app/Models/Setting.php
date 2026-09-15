<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'logo',
        'logo_dark',
        'logo_light',
        'favicon',
        'phone',
        'phone2',
        'email',
        'email2',
        'alert_email',
        'address',
        'map_url',
        'description',
        'copyright_text',
        'social_links',
        'hero_video_url',
    ];

    /**
     * Get YouTube embed URL for hero section video.
     */
    public function getHeroVideoEmbedUrlAttribute(): ?string
    {
        $url = trim($this->hero_video_url ?? '');
        if (empty($url)) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $url;
    }

    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Always return the single site settings record (singleton pattern).
     */
    public static function getSiteSettings(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'company_name' => 'InvestHub',
            'email' => 'info@investhub.com',
            'phone' => '+880 1XXX-XXXXXX',
            'address' => 'Dhaka, Bangladesh',
            'description' => 'Invest in real import products and earn weekly profit.',
            'copyright_text' => '© ' . date('Y') . ' InvestHub. All rights reserved.',
            'logo' => '',
            'logo_dark' => '',
            'logo_light' => '',
            'favicon' => '',
            'social_links' => [
                'facebook' => 'https://facebook.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram.com',
                'youtube' => '',
                'linkedin' => '',
                'whatsapp' => '',
            ],
        ]);
    }

    /**
     * Resolve a stored asset path to its public URL.
     */
    protected function resolveAssetUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $publicPath = public_path(ltrim($path, '/'));

        if (file_exists($publicPath)) {
            return asset($path);
        }

        return null;
    }

    /**
     * Primary logo used across the app, preferring the default logo then theme variants.
     */
    public function getPrimaryLogoUrlAttribute(): ?string
    {
        return $this->resolveAssetUrl($this->logo)
            ?? $this->resolveAssetUrl($this->logo_light)
            ?? $this->resolveAssetUrl($this->logo_dark);
    }

    /**
     * Dark theme logo used in dark backgrounds.
     */
    public function getDarkLogoUrlAttribute(): ?string
    {
        return $this->resolveAssetUrl($this->logo_dark)
            ?? $this->resolveAssetUrl($this->logo_light)
            ?? $this->resolveAssetUrl($this->logo);
    }

    /**
     * Light theme logo used on dark sections.
     */
    public function getLightLogoUrlAttribute(): ?string
    {
        return $this->resolveAssetUrl($this->logo_light)
            ?? $this->resolveAssetUrl($this->logo_dark)
            ?? $this->resolveAssetUrl($this->logo);
    }

    /**
     * Get active logo URL (falls back to text logo).
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->primary_logo_url;
    }

    /**
     * Get favicon URL.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->resolveAssetUrl($this->favicon);
    }
}
