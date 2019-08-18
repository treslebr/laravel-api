<?php

use Illuminate\Database\Seeder;

/**
 * @todo Tresle
 */
class AdditionalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Tresle\Product\Model\Additional\Additional::create(array(
            'name' => 'Mussarela',
            "price" => 2,
            "product_additional_category_id" => 1
        ));

        \Tresle\Product\Model\Additional\Additional::create(array(
            'name' => 'Ovo',
            "price" => 1,
            "product_additional_category_id" => 1
        ));

        \Tresle\Product\Model\Additional\Additional::create(array(
            'name' => 'Frango desfiado',
            "price" => 3,
            "product_additional_category_id" => 1
        ));
    }
}
