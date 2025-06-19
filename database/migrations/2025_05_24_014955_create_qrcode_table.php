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
        Schema::create('qrcode', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('morning_checkin_time');
            $table->string('morning_checkout_time');
            $table->string('afternoon_checkin_time');
            $table->string('afternoon_checkout_time');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('embed_url');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('company') // Also consider renaming to 'companies' (Laravel convention)
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcode');
    }
};
