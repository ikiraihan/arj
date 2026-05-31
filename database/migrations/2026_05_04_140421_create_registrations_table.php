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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('racer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('registration_number')->nullable();
            $table->enum('status', ['unpaid','menunggu-pembayaran','paid','rejected', 'menunggu-approval'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->string('payment_proof')->nullable();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('racer_id')->references('id')->on('racers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
