<?php

namespace App\Imports;

use App\CodePanne;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CodePannesImport implements ToModel,WithHeadingRow{
    
    public function model(array $row){
        return new CodePanne([
            "designation" => $row["designation"],
            "code" => $row["code"]
        ]);
    }
}