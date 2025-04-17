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
        Schema::create('farm_products', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('product_id');

            $table->decimal('sell_quantity', 8, 2);
            $table->decimal('price_sell_quantity', 8, 2);
            $table->enum('unit', ['kg', 'l', 'ks']);
            $table->decimal('price_per_unit', 8, 2);
            $table->integer('discount_percentage')->nullable();
            $table->text('farm_specific_description')->nullable();
            $table->boolean('availability')->default(true);
            $table->decimal('rating', 2, 1)->nullable();

            $table->timestamps();

            $table->foreign('farm_id')
                ->references('id')
                ->on('farms')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_products');
    }
};
