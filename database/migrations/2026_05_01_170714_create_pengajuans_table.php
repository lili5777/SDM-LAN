<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->enum('jenis', ['upload', 'hapus']);
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumen')->onDelete('set null'); // diisi jika jenis=hapus
            $table->foreignId('folder_id')->constrained('folder')->onDelete('restrict');
            $table->string('judul');
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->string('file_path')->nullable(); // file sementara untuk upload
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->unsigned()->nullable();
            $table->string('file_type')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('alasan_pengajuan')->nullable(); // wajib untuk hapus
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Index untuk filter
            $table->index(['pegawai_id', 'status']);
            $table->index(['jenis', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};