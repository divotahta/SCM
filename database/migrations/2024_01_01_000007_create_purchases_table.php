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
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('disetujui_pada')->nullable();
            $table->foreignId('ditolak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('ditolak_pada')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('diterima_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('diterima_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}; 