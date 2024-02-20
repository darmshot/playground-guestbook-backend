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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('payment_date');
            $table->string('payer_name',64);
            $table->string('payer_surname',64);
            $table->unsignedDecimal('amount');
            $table->string('national_security_number',10)->nullable();
            $table->enum('status', ['ASSIGNED', 'PARTIALLY_ASSIGNED']);
            $table->tinyText('description');
            $table->string('payment_reference',36)->unique()->nullable();
            $table->uuid('ref_id')->unique()->nullable();
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
