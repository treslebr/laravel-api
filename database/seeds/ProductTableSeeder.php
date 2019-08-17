<?php

use Illuminate\Database\Seeder;

/**
 * @todo Tresle
 */
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tresle\Product\Model\Product\Product::create(array(
            'name'  => 'X-Tudo',
            "price" => 15,
            "product_category_id" => 1
        ));

        Tresle\Product\Model\Product\Product::create(array(
            'name'  => 'X-Picanha',
            "price" => 18,
            "product_category_id" => 1
        ));
    }
}
