<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->decimal('paid_amount', 12, 2);
            $table->decimal('change_amount', 12, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'bank_transfer']);
            $table->enum('status', ['completed', 'void'])->default('completed');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 