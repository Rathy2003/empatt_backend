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
        Schema::table('qrcode', function (Blueprint $table) {
            $table->string('checkin_time')->after('image');
            $table->string('checkout_time')->after('checkin_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qrcode', function (Blueprint $table) {
            $table->dropColumn('checkin_time');
            $table->dropColumn('checkout_time');
        });
    }
};
