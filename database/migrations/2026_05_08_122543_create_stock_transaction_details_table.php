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
    Schema::create('stock_transaction_details', function (Blueprint $table) {
        $table->id();

        $table->foreignId('stock_transaction_id')
            ->constrained('stock_transactions')
            ->cascadeOnDelete();

        $table->foreignId('item_id')
            ->constrained('items')
            ->restrictOnDelete();

        $table->integer('quantity');
        $table->integer('stock_before');
        $table->integer('stock_after');

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transaction_details');
    }
};
