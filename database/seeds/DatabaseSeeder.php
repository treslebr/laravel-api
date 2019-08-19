<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @todo Tresle
         */
        $this->call(UserTableSeeder::class);
        $this->call(AdditionalCategoryTableSeeder::class);
        $this->call(AdditionalTableSeeder::class);
        $this->call(ProductCategoryTableSeeder::class);
        $this->call(ProductTableSeeder::class);
    }
}
