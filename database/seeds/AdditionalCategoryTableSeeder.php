<?php

use Illuminate\Database\Seeder;

/**
 * @todo Tresle
 */
class AdditionalCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Tresle\Product\Model\Additional\Category::create(array(
            'name' => 'Hamburguer'
        ));
    }
}
