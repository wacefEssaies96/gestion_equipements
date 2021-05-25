<?php

namespace App\Imports;

use App\Historique;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class HistoriqueImport implements ToModel,WithHeadingRow{
    
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
        if($row['cloture'] == 'Oui') $cloture = 'Cloturé';
        else $cloture = 'Non Cloturé';
        
        $hist = new Historique([
            "travaille" => $row["trv_effectue"],
            "description_demande" => $row["description_dmd"],
            "appelle" => $cloture,
            "jour" => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["date_demande"])),
            "heur_dedmande" => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["heure_demande"])),

            "etat" => $row["etat"],
            "zone" => $zone,
        ]);
        return $hist;
    }
}