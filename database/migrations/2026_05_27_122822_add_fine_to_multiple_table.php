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
            if(!Schema::hasColumn('event_classes', 'price_fine')) {
                $table->decimal('price_fine', 12, 2)->default(0)->after('price');
            }
        });

        Schema::table('registrations', function (Blueprint $table) {
            if(!Schema::hasColumn('registrations', 'is_fined')) {
                $table->boolean('is_fined')->default(0)->after('payment_proof');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if(Schema::hasColumn('registrations', 'is_fined')) {
                $table->dropColumn('is_fined');
            }
        });

        Schema::table('event_classes', function (Blueprint $table) {
            if(Schema::hasColumn('event_classes', 'price_fine')) {
                $table->dropColumn('price_fine');
            }
        });
    }
};
