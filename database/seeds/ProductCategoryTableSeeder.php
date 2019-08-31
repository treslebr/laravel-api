<?php

use Illuminate\Database\Seeder;

/**
 * @todo Tresle
 */
class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Tresle\Product\Model\Product\Category::create(array(
            'name' => 'Lanches'
        ));
    }
}
