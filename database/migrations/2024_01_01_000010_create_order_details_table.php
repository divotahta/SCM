<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('products')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}; 