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
        Schema::create('pembayarans', function (Blueprint $table) {
        $table->id(); // Primary key untuk tabel pembayaran
        // Ini buat relasi ke tabel murids
        $table->foreignId('id_murid')->constrained('murids', 'id_murid')->onDelete('cascade');
        $table->integer('nominal'); // Nominal bayar (misal: 5000)
        $table->enum('tipe', ['masuk', 'keluar']); // Jenis pembayaran
        $table->dateTime('tanggal_bayar');
        $table->string('keterangan')->nullable(); // Misal: "Kas Minggu ke-1 Mei"
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
