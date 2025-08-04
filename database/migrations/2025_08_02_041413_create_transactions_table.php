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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_price');

            $table->enum('payment_method', ['cod', 'transfer']);
            $table->enum('payment_status', ['pending', 'paid', 'cenceled'])->default('pending');

            $table->enum('status_transaction', ['requested', 'accepted', 'rejected', 'completed'])->default('requested');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
