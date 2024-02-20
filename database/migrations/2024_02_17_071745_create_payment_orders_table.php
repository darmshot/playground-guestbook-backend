<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('state', ['ACTIVE', 'PAID']);
            $table->decimal('amount');
            $table->foreignUuid('payment_id')->unique()->constrained('payments')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
