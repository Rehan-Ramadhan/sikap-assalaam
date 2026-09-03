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
        Schema::create('intervention_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('intervention_id')
                ->constrained('interventions')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->restrictOnDelete();

            $table->enum('status', [
                'ditunggu',
                'pembinaan',
                'selesai'
            ]);

            $table->text('catatan')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_logs');
    }
};
