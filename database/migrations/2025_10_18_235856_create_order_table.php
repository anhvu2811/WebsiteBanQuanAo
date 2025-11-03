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
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('order_date');
            $table->unsignedBigInteger('customer_id');
            $table->float('total_price');
            $table->string('coupon_code');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('shipping_address');
            $table->text('notes');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('tbl_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order');
    }
};