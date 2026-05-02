<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 50)->unique();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('golongan', 10)->nullable(); // III/a, III/b, IV/a
            $table->string('jabatan')->nullable();
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('restrict');
            $table->enum('status', ['aktif', 'pensiun', 'mutasi', 'berhenti'])->default('aktif');
            $table->string('no_hp', 20)->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};