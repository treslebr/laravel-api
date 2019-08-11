<?php

use Illuminate\Database\Seeder;

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
        factory(\App\User::class)->create([
            'email' => "peterclayder@gmail.com"
        ]);

        factory(\App\User::class)->create([
            'email' => "fpires@id.uff.br"
        ]);
    }
}
