<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investment_posts', function (Blueprint $table) {
            $table->id();

            // Product Details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->integer('total_quantity');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('profit_per_unit', 12, 2);
            $table->integer('expected_import_days');

            // Investment Financial Target & Progress
            $table->decimal('target_amount', 15, 2); // Calculated: total_quantity * unit_cost
            $table->decimal('current_invested_amount', 15, 2)->default(0.00);
            $table->decimal('min_investment_amount', 12, 2)->default(1000.00);
            $table->string('type')->default('Import');

            $table->enum('type', ['Import', 'Local', 'Manufacture'])->default('Import');
            $table->string('msg_profit_payment')->default('Weekly');

            // Post Status Options matching UI
            $table->enum('status', ['upcoming', 'active', 'imported', 'sold_out', 'completed'])->default('active');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_posts');
    }
};
