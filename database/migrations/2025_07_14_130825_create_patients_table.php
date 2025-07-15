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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_number')->unique(); // Nomor pasien
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('blood_type')->nullable(); // Golongan darah
            $table->text('allergies')->nullable(); // Alergi
            $table->text('medical_history')->nullable(); // Riwayat penyakit
            $table->string('emergency_contact_name'); // Kontak darurat
            $table->string('emergency_contact_phone'); // Telepon kontak darurat
            $table->string('insurance_number')->nullable(); // Nomor asuransi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
