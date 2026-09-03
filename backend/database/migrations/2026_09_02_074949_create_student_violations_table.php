<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('violation_categories')
                ->restrictOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff')
                ->restrictOnDelete();

            $table->date('tanggal_kejadian');

            $table->text('keterangan')->nullable();

            /*
             * Snapshot poin pada saat pelanggaran dicatat.
             * Jadi kalau master poin berubah,
             * riwayat lama tidak ikut berubah.
             */
            $table->integer('poin_tercatat');

            $table->enum('status', [
                'aktif',
                'dibatalkan'
            ])->default('aktif');

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('cancelled_at')->nullable();

            $table->text('cancel_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_violations');
    }
};
