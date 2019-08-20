<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_additional', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            // order item
            $table->bigInteger("order_item_id")->unsigned();
            $table->foreign("order_item_id")->references('id')->on("order_item");
            // additional
            $table->string('additional_name');
            $table->double('additional_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_additional');
    }
}
