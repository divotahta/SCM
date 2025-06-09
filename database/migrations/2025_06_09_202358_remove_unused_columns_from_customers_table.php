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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'jenis',
                'nama_bank',
                'pemegang_rekening',
                'nomor_rekening'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('email')->unique()->nullable();
            $table->string('jenis')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('pemegang_rekening')->nullable();
            $table->string('nomor_rekening')->nullable();
        });
    }
};
