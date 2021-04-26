<?php

namespace App\Imports;

use App\Equipement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class EquipementImport implements ToModel,WithHeadingRow{
    
    public function model(array $row){
        switch ($row['code_section']) {
            case 1:
              $zone = "Coupe";
              break;
            case 2:
                $zone = "Préparation";
                break;
            case 3:
                $zone = "Assemblage";
                break;
            case 4:
                $zone = "Controle éléctrique";
                break;
            case 5:
                $zone = "Sertissage";
                break;
            default:
                $zone = "0";
        }
        $equipement = new Equipement([
            "code" => $row["Code_Machine"] ?? $row["code_machine"],
            "designation" => $row["Designation"] ?? $row["designation"],
            "code_section" => $row["Code_Section"] ?? $row["code_section"],
            "emplacement" => $row["Code_Emp"] ?? $row["code_emp"], 
            "constructeur" => $row["Code_Const"] ?? $row["code_const"],
            "code_categorie" => $row["Code_Cat"] ?? $row["code_cat"],
            "n_serie" => $row["N_Serie"] ?? $row["n_serie"],
            "date_acq" => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["date_acq"])),
            "etat" => $row["etat"],
            "zone" => $zone,
        ]);
        return $equipement;
    }
}