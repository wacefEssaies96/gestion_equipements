<?php

namespace App\Imports;

use App\Equipement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipementImport implements ToModel,WithHeadingRow{
    
    public function model(array $row){
        return new Equipement([
            "nom" => $row["nom"],
            "code" => $row["code"],
            "n_serie" => $row["n_serie"],
            "designation" => $row["designation"],
            "zone" => $row["zone"],
        ]);
    }
}