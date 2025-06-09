<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'received'])->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
                'received_at',
                'received_by'
            ]);
        });
    }
}; 