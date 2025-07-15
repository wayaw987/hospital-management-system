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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('specialist'); // Spesialisasi dokter
            $table->text('address');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('license_number')->unique(); // Nomor izin praktek
            $table->integer('experience_years'); // Tahun pengalaman
            $table->decimal('consultation_fee', 10, 2); // Biaya konsultasi
            $table->text('education'); // Riwayat pendidikan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
