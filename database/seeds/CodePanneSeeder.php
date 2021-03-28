<?php

use Illuminate\Database\Seeder;
use App\CodePanne;
use Illuminate\Support\Str;

class CodePanneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      for($i=0;$i<200;$i++){
        CodePanne::create([
            'code' => Str::random(10),
            'designation' => 'Cofat',
            'zone' => 'Assemblage',
         ]);
        }
    }
}
