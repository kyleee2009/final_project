<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'item_id',
                'quantity',
                'stock_before',
                'stock_after',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->foreignId('item_id')
                ->nullable()
                ->after('transaction_code')
                ->constrained('items')
                ->restrictOnDelete();

            $table->integer('quantity')->nullable()->after('type');
            $table->integer('stock_before')->nullable()->after('quantity');
            $table->integer('stock_after')->nullable()->after('stock_before');
        });
    }
};