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
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('input_raw');
            $table->string('input_clean');
            $table->string('startup')->nullable();
            $table->string('on_block')->nullable();
            $table->string('on_hit')->nullable();
            $table->string('hit_level')->nullable();
            $table->text('notes')->nullable();
            $table->string('animation_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
