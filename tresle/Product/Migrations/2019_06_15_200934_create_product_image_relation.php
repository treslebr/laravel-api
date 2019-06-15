<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImageRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_image_relation', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger("product_id")->unsigned();
            $table->foreign("product_id")->references('id')->on("product");
            $table->string("src");

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
        Schema::dropIfExists('product_image_relation');
    }
}
