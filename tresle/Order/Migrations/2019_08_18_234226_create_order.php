<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            // Customer
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign("customer_id")->references('id')->on("users");
            // Address
            $table->string('postcode');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('region');
            $table->string('street_1');
            $table->string('street_2');
            $table->string('street_3')->nullable();
            $table->string('shipping_location');
            $table->string('shipping_price');
            // Status
            $table->string('status')->default("pending");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
