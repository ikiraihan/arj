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
        Schema::table('registrations', function (Blueprint $table) {
            if(!Schema::hasColumn('registrations', 'racer_number')) {
                $table->integer('racer_number')->nullable()->after('registration_number');
            }
        });

        Schema::table('racers', function (Blueprint $table) {
            if(!Schema::hasColumn('racers', 'racer_number')) {
                $table->integer('racer_number')->nullable()->after('short_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if(Schema::hasColumn('registrations', 'racer_number')) {
                $table->dropColumn('racer_number');
            }
        });

        Schema::table('racers', function (Blueprint $table) {
            if(Schema::hasColumn('racers', 'racer_number')) {
                $table->dropColumn('racer_number');
            }
        });
    }
};
