<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
         // Create Admin
         User::create([
            'nom' => 'COFAT',
            'prenom' => 'Cofat',
            'email' => 'admin@cofat.com',
            'tel' => '00525755',
            'password' => bcrypt('password'),
            'role' => 'ADMIN',
         ]);
    }
}
