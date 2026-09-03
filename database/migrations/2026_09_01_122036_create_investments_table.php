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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('investment_post_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->integer('calculated_quantity_share'); // Investor unit share
            $table->decimal('per_piece_profit', 15, 2);
            $table->decimal('expected_profit', 15, 2);
            $table->enum('status', ['pending','active','sold','completed','cancelled','refunded'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
