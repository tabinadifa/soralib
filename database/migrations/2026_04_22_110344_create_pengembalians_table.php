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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                ->constrained('peminjaman')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->date('tanggal_pengembalian');
            $table->enum('kondisi_buku', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('metode_pembayaran', ['QRIS', 'tunai', 'tidak_denda'])->nullable();
            $table->enum('status', ['lunas', 'belum_lunas'])->nullable();
            $table->integer('denda')->default(0);
            $table->foreignId('file_bukti_pembayaran_id')
                ->nullable()
                ->constrained('file_managers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
