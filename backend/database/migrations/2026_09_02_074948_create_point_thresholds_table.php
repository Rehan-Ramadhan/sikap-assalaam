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
        Schema::create('point_thresholds', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('poin_minimal');

            $table->enum('tahap', [
                'wali_kelas',
                'bk',
                'kesiswaan',
                'kepala_sekolah'
            ]);

            $table->string('nama_tindakan', 100);

            $table->text('deskripsi')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_thresholds');
    }
};
