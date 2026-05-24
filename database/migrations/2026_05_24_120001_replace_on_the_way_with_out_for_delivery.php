<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('orders')
            ->where('status', 'on_the_way')
            ->update(['status' => 'out_for_delivery']);

        DB::table('order_status_histories')
            ->where('status', 'on_the_way')
            ->update(['status' => 'out_for_delivery']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('orders')
            ->where('status', 'out_for_delivery')
            ->update(['status' => 'on_the_way']);

        DB::table('order_status_histories')
            ->where('status', 'out_for_delivery')
            ->update(['status' => 'on_the_way']);
    }
};

