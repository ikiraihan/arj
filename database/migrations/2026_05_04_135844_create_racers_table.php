<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('racers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nik')->nullable();
            $table->string('full_name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('birth_location')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('hometown')->nullable();
            $table->string('photo')->nullable();
            $table->string('kta')->nullable();
            $table->string('kis')->nullable();

            $table->timestamps();

            // Optional: relasi ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('racers');
    }
};
