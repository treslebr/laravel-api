<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAdditionalRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_additional_relation', function (Blueprint $table) {
            $table->bigInteger("product_id")->unsigned();
            $table->foreign("product_id", "product_prod_foreign")
                ->references('id')
                ->on("product")
                ->onDelete('cascade');

            $table->bigInteger("product_additional_id")->unsigned();
            $table->foreign("product_additional_id", "product_add_foreign")
                ->references('id')
                ->on("product_additional")
                ->onDelete('cascade');;

            $table->timestamps();

            $table->primary(['product_id', 'product_additional_id'], "prod_add");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_additional_relation');
    }
}
