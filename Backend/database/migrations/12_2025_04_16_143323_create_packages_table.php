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
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('farm_id');

            $table->decimal('price', 8, 2);
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', ['waiting', 'confirmed', 'shipped', 'delivered']);

            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('farm_id')
                ->references('id')
                ->on('farms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
