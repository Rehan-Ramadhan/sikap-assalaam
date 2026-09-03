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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->restrictOnDelete();

            $table->foreignId('threshold_id')
                ->nullable()
                ->constrained('point_thresholds')
                ->nullOnDelete();

            $table->enum('tahap', [
                'wali_kelas',
                'bk',
                'kesiswaan',
                'kepala_sekolah'
            ]);

            $table->unsignedInteger('poin_saat_penanganan');

            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai'
            ])->default('menunggu');

            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai')
                ->nullable();

            $table->text('catatan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
