<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_classes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('cc')->nullable(); // opsional
            $table->text('notes')->nullable();

            $table->timestamps();

            // Relasi ke tabel events
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_classes');
    }
};
