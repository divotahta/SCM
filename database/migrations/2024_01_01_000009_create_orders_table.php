<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('customers')->onDelete('cascade');
            $table->date('tanggal_pesanan');
            $table->enum('status_pesanan', ['pending', 'processing', 'completed', 'cancelled']);
            $table->integer('total_produk');
            $table->decimal('sub_total', 10, 2);
            $table->decimal('pajak', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('nomor_faktur')->unique();
            $table->enum('jenis_pembayaran', ['cash', 'transfer', 'credit']);
            $table->decimal('bayar', 10, 2);
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 