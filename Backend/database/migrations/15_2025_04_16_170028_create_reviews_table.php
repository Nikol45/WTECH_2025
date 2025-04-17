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
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedBigInteger('farm_product_id');
            $table->unsignedBigInteger('user_id');

            $table->string('title');
            $table->decimal('rating', 2, 1);
            $table->text('text')->nullable();

            $table->timestamps();

            $table->foreign('farm_product_id')
                ->references('id')
                ->on('farm_products')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
