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
            $table->id();

            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('address_id')->nullable();

            $table->string('name');
            $table->text('description')->nullable();
            $table->float('rating')->nullable();
            $table->boolean('delivery_available')->default(true);
            $table->decimal('min_delivery_price', 8, 2)->nullable();
            $table->integer('avg_delivery_time')->nullable();
            $table->string('banner_picture')->nullable();


            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('set null');
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
