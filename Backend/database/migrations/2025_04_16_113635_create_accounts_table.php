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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->unsignedBigInteger('delivery_address_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->string('username');
            $table->string('password');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->boolean('admin_account')->default(false);
            $table->string('profile_picture')->nullable();

            $table->timestamps();

            $table->foreign('billing_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('set null');

            $table->foreign('delivery_address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('set null');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
