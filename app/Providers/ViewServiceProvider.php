<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * Share $setting with ALL views globally.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $setting = Setting::getSiteSettings();
            } catch (\Exception $e) {
                // During initial migrations the table may not exist yet
                $setting = new Setting([
                    'company_name' => 'InvestHub',
                    'email' => '',
                    'phone' => '',
                    'address' => '',
                    'description' => '',
                    'copyright_text' => '© ' . date('Y') . ' InvestHub.',
                    'social_links' => [],
                ]);
            }
            $view->with('setting', $setting);
        });
    }
}
