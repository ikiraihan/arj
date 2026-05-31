<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();
            $table->text('notes_transfer')->nullable();
            $table->string('venue')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->text('link_maps')->nullable();
            $table->datetime('registration_start_date')->nullable();
            $table->datetime('registration_end_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('link_documentation')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
