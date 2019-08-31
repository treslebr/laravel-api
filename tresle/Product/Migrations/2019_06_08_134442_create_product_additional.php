<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_additional', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->boolean("status")->default(true);
            $table->float("price")->default(0);
            $table->bigInteger("product_additional_category_id")->unsigned();
            $table->foreign("product_additional_category_id")->references('id')->on("product_additional_category");
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
        Schema::dropIfExists('product_additional');
    }
}
