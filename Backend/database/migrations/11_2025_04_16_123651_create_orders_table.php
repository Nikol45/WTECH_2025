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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('billing_address_id');
            $table->unsignedBigInteger('delivery_address_id');
            $table->unsignedBigInteger('company_id')->nullable();

            $table->decimal('total_price', 8, 2)->nullable();
            $table->enum('payment_type', ['online', 'transfer', 'cash']);
            $table->enum('delivery_type', ['in_person', 'express', 'standard']);
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('billing_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');

            $table->foreign('delivery_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
