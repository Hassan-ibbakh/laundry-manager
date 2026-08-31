<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['laundry_id', 'phone']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['laundry_id', 'created_at']);
            $table->index(['laundry_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_laundry_id_phone_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_laundry_id_created_at_index');
            $table->dropIndex('orders_laundry_id_status_created_at_index');
        });
    }
};