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
        Schema::create('shift_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('admin_id');
            $table->integer('day')->nullable();;
            $table->time('start_time')->nullable();
            $table->time('start_of_shift_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('status')->default(0);
            $table->time('end_of_shift_time')->nullable();
            $table->integer('monday')->nullable();
            $table->integer('tuesday')->nullable();
            $table->integer('wednesday')->nullable();
            $table->integer('thursday')->nullable();
            $table->integer('friday')->nullable();
            $table->integer('saturday')->nullable();
            $table->integer('sunday')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_cards');
    }
};
