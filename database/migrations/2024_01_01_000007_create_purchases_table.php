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
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status_pembelian', ['draft', 'pending', 'approved', 'rejected', 'received'])->default('draft');
            $table->timestamp('disetujui_pada')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');
            $table->timestamp('ditolak_pada')->nullable();
            $table->foreignId('ditolak_oleh')->nullable()->constrained('users');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('diterima_pada')->nullable();
            $table->foreignId('diterima_oleh')->nullable()->constrained('users');
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->foreignId('diperbarui_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}; 