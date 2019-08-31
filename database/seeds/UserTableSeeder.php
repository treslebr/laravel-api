<?php

use Illuminate\Database\Seeder;
use Tresle\User\Model\User;

/**
 * @todo Tresle
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(array(
            'name' => 'admin',
            'password' => Hash::make('admin123'),
            'email' => "admin@gmail.com",
            'telephone' => "21971579961",
            'is_admin' => true,
        ));

        User::create(array(
            'name' => 'customer',
            'password' => Hash::make('customer123'),
            'email' => "customer@gmail.com",
            'telephone' => "21971579961",
        ));
    }
}
