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
            if(!Schema::hasColumn('registrations', 'race_status_approved_at')) {
                $table->timestamp('race_status_approved_at')->nullable()->after('race_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if(Schema::hasColumn('registrations', 'race_status_approved_at')) {
                $table->dropColumn('race_status_approved_at');
            }
        });
    }
};
