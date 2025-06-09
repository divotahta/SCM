<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jenis')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('pemegang_rekening')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('total_pembelian', 12, 2)->default(0);
            $table->enum('loyalty_level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->integer('points')->default(0);
            $table->timestamp('last_purchase_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}; 