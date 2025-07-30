<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DocumentoFaltanteExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct($data){
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['Apellido_Paterno','Apellido_Materno','Nombre','Carrera',
            'Cert_Prepa','Acta_Nacimiento','CURP','Afiliación','Sit_Migratoria'];
    }
}
