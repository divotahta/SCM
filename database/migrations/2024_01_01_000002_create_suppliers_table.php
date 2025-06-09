<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('telepon');
            $table->text('alamat');
            $table->string('nama_toko');
            $table->string('jenis');
            $table->string('nama_bank')->nullable();
            $table->string('pemegang_rekening')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}; 