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
        Schema::table('event_classes', function (Blueprint $table) {
            if(!Schema::hasColumn('event_classes', 'quota')) {
                $table->integer('quota')->nullable()->after('cc');
            }
            $table->softDeletes();
        });
        Schema::table('registrations', function (Blueprint $table) {
            if(!Schema::hasColumn('registrations', 'team_name')) {
                $table->string('team_name')->nullable()->after('registration_number');
            }
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_classes', function (Blueprint $table) {
            if(Schema::hasColumn('event_classes', 'quota')) {
                $table->dropColumn('quota');
            }
            $table->dropSoftDeletes();
        });
        Schema::table('registrations', function (Blueprint $table) {
            if(Schema::hasColumn('registrations', 'team_name')) {
                $table->dropColumn('team_name');
            }
        });
    }
};
