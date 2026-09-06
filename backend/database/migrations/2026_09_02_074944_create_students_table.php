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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('nis', 20)->unique();

            $table->enum('tingkat', ['10', '11', '12']);
            $table->enum('jurusan', ['RPL', 'TSM', 'TKR']);
            $table->unsignedTinyInteger('nomor_kelas');

            $table->year('tahun_masuk');

            $table->enum('status', [
                'aktif',
                'nonaktif',
                'lulus'
            ])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
