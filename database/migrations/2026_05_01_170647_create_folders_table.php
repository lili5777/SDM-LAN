<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_folder_id')->constrained('kategori_folder')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->json('ekstensi_allowed')->nullable(); // ["pdf","jpg","png"]
            $table->integer('max_size_mb')->default(10);
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folder');
    }
};