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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cash_on_delivery')->after('delivery_address');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('payment_transaction_ref')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_transaction_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'payment_transaction_ref',
                'paid_at',
            ]);
        });
    }
};

