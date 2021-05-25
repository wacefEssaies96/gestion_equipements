<?php

namespace App\Exports;

use App\Historique;
use Maatwebsite\Excel\Concerns\FromCollection;

class InterventionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Historique::all();
    }
}
