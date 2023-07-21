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
            $table->bigIncrements('id');
            $table->date('ordered_on')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('name')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('postal_code')->nullable(false);
            $table->string('address_1')->nullable(false);
            $table->string('address_2')->nullable(false);
            $table->string('address_3')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->timestamps();
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
