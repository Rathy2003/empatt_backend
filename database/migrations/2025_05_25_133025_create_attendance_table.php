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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->dateTime('scan_at');
            $table->string('location');
            $table->enum('present', ['Present', 'Absent'])->default(null);
            $table->enum('absent', ['Present', 'Absent'])->default(null);
            $table->decimal('total_wages',10,2)->default(0);
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
