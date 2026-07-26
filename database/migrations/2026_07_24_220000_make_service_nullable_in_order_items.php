<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Rendre la colonne service nullable pour éviter l'erreur SQL
            $table->enum('service', ['غسيل', 'كي', 'غسيل+كي'])->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('service', ['غسيل', 'كي', 'غسيل+كي'])->nullable(false)->change();
        });
    }
};
