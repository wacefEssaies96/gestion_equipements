<?php

use Illuminate\Database\Seeder;
use App\Equipement;
use Illuminate\Support\Str;

class Equipements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zone = ['Assemblage', 'Sertissage', 'Controle élétrique', 'Coupe', 'Préparation'];
        for($i=0;$i<1000;$i++){
            Equipement::create([
                'nom' => Str::random(10),
                'code' => Str::random(10),
                'designation' => Str::random(10),
                'n_serie' => Str::random(10),
                'zone' => 'Assemblage',//$zone[rand(0,4)]
                ]);
            }
    }
}
