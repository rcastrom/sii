<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ListasExport implements FromCollection
{
    private $data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
        $this->data=$data;
    }
    public function collection()
    {
        return $this->data;
    }
    public function headings():array
    {
        return ['No control', 'Apellido Paterno', 'Apellido Materno', 'Nombre'];
    }
}
