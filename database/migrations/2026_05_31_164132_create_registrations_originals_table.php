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
        Schema::create('registrations_originals', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('racer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('registration_number')->nullable();
            $table->string('team_name')->nullable();
            $table->text('notes')->nullable();
            $table->integer('invoice_number')->nullable();
            $table->string('vehicle')->nullable();
            $table->integer('vehicle_number')->nullable();
            $table->integer('rangka_number')->nullable();
            $table->string('name_register')->nullable();
            $table->string('phone_number_register')->nullable();
            $table->integer('racer_number')->nullable();
            $table->string('payment_method')->nullable();
            $table->boolean('is_fined')->default(0);

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('racer_id')->references('id')->on('racers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('event_classes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations_originals');
    }
};
