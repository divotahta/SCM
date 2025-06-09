<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pembelian');
            $table->string('nomor_pembelian')->unique();
            $table->foreignId('pemasok_id')->constrained('suppliers')->onDelete('cascade');
            $table->enum('status_pembelian', ['pending', 'completed', 'cancelled']);
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->foreignId('diperbarui_oleh')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}; 