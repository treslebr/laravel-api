<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign("customer_id")->references('id')->on("users");
            $table->integer("qty")->default(1);
            $table->longText("obs")->nullable();
            $table->bigInteger("product_id")->unsigned();
            $table->foreign("product_id")->references('id')->on("product");
            $table->json("additionals_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
