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
        Schema::create('registration_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->integer('invoice_number')->nullable();
            // $table->integer('racer_number')->nullable();
            $table->string('vehicle')->nullable();
            $table->integer('vehicle_number')->nullable();
            $table->integer('rangka_number')->nullable();

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('event_classes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_classes');
    }
};
