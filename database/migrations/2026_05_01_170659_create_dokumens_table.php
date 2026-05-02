<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('folder_id')->constrained('folder')->onDelete('restrict');
            $table->string('judul');
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size')->unsigned(); // bytes
            $table->string('file_type'); // mime type
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'diarsipkan'])->default('aktif');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['pegawai_id', 'folder_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};