<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pieces_count', 'pieces_type', 'pieces_color']);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('pieces_count')->nullable();
            $table->string('pieces_type')->nullable();
            $table->string('pieces_color')->nullable();
        });
    }
};