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
        Schema::create('flag_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_flag_id')->constrained()->cascadeOnDelete();
            $table->string('attribute'); // e.g., "country" or "plan"
            $table->string('operator'); // e.g., "equals", "in"
            $table->json('values'); // e.g., ["DE", "AU"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flag_rules');
    }
};
