<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FichasExport implements FromCollection, WithHeadings
{
    private $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    public function collection(): Collection
    {
        return $this->data;
    }
    public function headings():array
    {
        return ['Ficha','Nombre','Apellido Paterno', 'Apellido Materno', 'No de control',
            'Carrera','Fecha nacimiento','Sexo','País de nacimiento',
            'Estado de nacimiento','Municipio de nacimiento','Etnia','CURP',
            'Teléfono','Calle_número','Colonia','Estado de residencia',
            'Municipio de residencia','Correo','Facebook','Instagram',
            'Preparatoria','Estado de la preparatoria','Municipio de preparatoria','Egreso',
            'Promedio','Alergia','Enfermedad conocida','Tipo sangre'];
    }
}
