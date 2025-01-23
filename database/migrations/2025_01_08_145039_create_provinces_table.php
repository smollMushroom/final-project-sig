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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(false);
            $table->integer('total_visitors')->nullable(false);
            $table->integer('labor_wages_avg')->nullable(false);
            $table->integer('total_SD')->nullable(false);
            $table->integer('total_SMP')->nullable(false);
            $table->integer('total_SMA')->nullable(false);
            $table->integer('total_SMK')->nullable(false);
            $table->longText('coordinates')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
