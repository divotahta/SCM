<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->string('kode_produk')->unique();
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->integer('stok');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('gambar_produk')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 