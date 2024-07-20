<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonalExport implements FromCollection, WithHeadings
{
    private $data;

    /**
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings():array
    {
        return ['Apellido Paterno', 'Apellido Materno', 'Nombre',
            'RFC','CURP','Num Empleado','Gob','SEP','Rama','Estatus',
            'Correo','Correo Institucional'];
    }
}
