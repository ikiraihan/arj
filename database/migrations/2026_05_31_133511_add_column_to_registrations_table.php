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
            if(!Schema::hasColumn('registrations', 'name_register')) {
                $table->string('name_register')->nullable()->after('user_id');
            }
            if(!Schema::hasColumn('registrations', 'phone_number_register')) {
                $table->string('phone_number_register')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if(Schema::hasColumn('registrations', 'name_register')) {
                $table->dropColumn('name_register');
            }
            if(Schema::hasColumn('registrations', 'phone_number_register')) {
                $table->dropColumn('phone_number_register');
            }
        });
    }
};
