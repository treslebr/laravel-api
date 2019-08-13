<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('postcode');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('region');
            $table->string('street_1');
            $table->string('street_2');
            $table->string('street_3');
            $table->string('street_4')->nullable();
            $table->bigInteger("customer_id")->unsigned();
            $table->foreign("customer_id")->references('id')->on("users");
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
        Schema::dropIfExists('customer_address');
    }
}
