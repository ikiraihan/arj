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
        Schema::table('regulation_files', function (Blueprint $table) {
            if(!Schema::hasColumn('regulation_files', 'is_active')) {
                $table->integer('is_active')->default(true)->after('path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regulation_files', function (Blueprint $table) {
            if(Schema::hasColumn('regulation_files', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
