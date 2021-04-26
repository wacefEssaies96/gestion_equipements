<?php

namespace App\Exports;

use App\Equipement;
use Maatwebsite\Excel\Concerns\FromCollection;

class EquipementExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Equipement::all();
    }
}
