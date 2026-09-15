<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('InvestHub');
            $table->string('logo')->nullable();           // uploads/settings/logo.png
            $table->string('logo_dark')->nullable();      // dark mode logo
            $table->string('logo_light')->nullable();     // light mode logo
            $table->string('favicon')->nullable();        // favicon
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('alert_email')->nullable();
            $table->text('address')->nullable();
            $table->text('map_url')->nullable();
            $table->text('description')->nullable();
            $table->string('copyright_text')->nullable();
            $table->json('social_links')->nullable();     // {"facebook":"url","twitter":"url",...}
            $table->text('hero_video_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
