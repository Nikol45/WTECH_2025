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
        Schema::create('farms', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->boolean('delivery_available')->default(true);
            $table->decimal('min_delivery_price', 8, 2)->nullable();
            $table->integer('avg_delivery_time')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
