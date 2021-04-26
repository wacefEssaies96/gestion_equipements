<?php

namespace App\Exports;

use App\CodePanne;
use Maatwebsite\Excel\Concerns\FromCollection;

class CodePanneExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CodePanne::all();
    }
}
