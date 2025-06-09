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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pelanggan_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('total_harga', 12, 2);
            $table->decimal('total_bayar', 12, 2);
            $table->decimal('total_kembali', 12, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris']);
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
}; 