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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('investment_id')->nullable()->constrained('investments')->onDelete('set null');
            $table->foreignId('investment_post_id')->nullable()->constrained('investment_posts')->onDelete('set null');
            $table->foreignId('payment_method_id')->nullable();
            $table->foreignId('payment_reason_id')->nullable();
            $table->foreignId('ref_reason_id')->nullable();
            $table->string('payment_number')->unique();
            $table->string('transaction_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transfer_number')->nullable();
            $table->decimal('paid_amount', 15, 2);
            $table->timestamp('payment_date');
            $table->text('message')->nullable();
            $table->string('slip')->nullable();
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
