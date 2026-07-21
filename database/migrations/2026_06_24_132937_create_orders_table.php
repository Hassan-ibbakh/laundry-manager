<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_id')->constrained('laundries')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->integer('pieces_count');
            $table->string('pieces_type');
            $table->string('pieces_color')->nullable();
            $table->enum('service', ['غسيل', 'كي', 'غسيل+كي']);
            $table->decimal('price', 10, 2);
            $table->date('received_at');
            $table->enum('status', ['received', 'cleaning', 'ready', 'delivered'])->default('received');
            $table->string('tracking_token')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};