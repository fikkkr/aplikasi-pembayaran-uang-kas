<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // buat nullable supaya pengeluaran umum/umum tidak wajib punya periode kas
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            //
        });
    }
};
