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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->foreignId('kategori_id')
                ->constrained('kategori_buku')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('jumlah_stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('isbn')->unique()->nullable();
            $table->foreignId('gambar_buku_id')
                ->nullable()
                ->constrained('file_managers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
