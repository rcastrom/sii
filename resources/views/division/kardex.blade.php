@extends('adminlte::page')

@section('title', 'Kárdex de alumno')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        <p class="card-text">{{ $ncarrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
        <p class="card-text">Semestre {{ $alumno->semestre }}</p>
        <p class="card-text">Especialidad {{ $especialidad }}</p>
        <p class="card-text">Estatus actual: {{ $estatus->descripcion }}</p>
    </x-information>
    <x-additional>
        @slot('header','Kardex de alumno')
        @php
            $suma_total=0; $calificaciones_totales=0; $j=1;
            $tipos_mat=array("O2","R1","R2","RO","RP","2");
            $tipos_aprob=array('AC','RC','RU','PG');
        @endphp
        @foreach($calificaciones as $key=>$value)
            @if(!empty($value))
                <caption>{{$nombre_periodo[$key]->identificacion_larga}}</caption>
                <table class="table table-responsive table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Materia</th>
                        <th>Calificación</th>
                        <th>Oportunidad</th>
                        <th>Créditos</th>
                        <th>Observaciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=1;
                        $suma_creditos=0;
                        $promedio_semestre=0;
                        $suma_semestre=0;
                        $cal_sem=0;
                        $materias=1;
                    @endphp
                    @foreach($value as $data)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $data->nombre_completo_materia }}</td>
                            <td>{{ $data->calificacion <= 70 && in_array($data->tipo_evaluacion,$tipos_aprob)?'AC':($data->calificacion < 70?"NA":$data->calificacion) }}</td>
                            <td>{{ $data->descripcion_corta_evaluacion }}</td>
                            <td>{{ $data->creditos_materia }}</td>
                            @if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA'))
                                @if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4)
                                    <td>A curso especial</td>
                                @else
                                    <td></td>
                                @endif
                            @endif
                        </tr>
                        @if($data->calificacion>=70||in_array($data->tipo_evaluacion,$tipos_aprob))
                            @php
                                $suma_creditos+=$data->creditos_materia;
                            @endphp
                            @if(!in_array($data->tipo_evaluacion,$tipos_aprob))
                                @php
                                    $cal_sem+=$data->calificacion;
                                    $calificaciones_totales+=$data->calificacion;
                                    $materias+=1;
                                    $j++;
                                @endphp
                            @endif
                            @php
                                $suma_total+=$data->creditos_materia;
                            @endphp
                        @else($data->calificacion<70&&!in_array($data->tipo_evaluacion,$tipos_aprob))
                            @php
                                $materias+=1;
                            @endphp
                        @endif
                        @php
                            $suma_semestre+=$data->creditos_materia;
                            $i++;
                        @endphp
                    @endforeach
                    @php
                        $promedio=($materias-1)==0?0:round($cal_sem/($materias-1),2);
                    @endphp
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Créditos Aprobados/Solicitados</td>
                        <td>{{$suma_creditos}}/{{$suma_semestre}}</td>
                        <td>Promedio del semestre</td>
                        <td>{{$promedio}}</td>
                    </tr>
                    </tfoot>
                </table>
            @endif
        @endforeach
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Porcentaje de avance</th>
                <th>Promedio General</th>
            </tr>
            <tr>
                <td align="center">
                    @php
                        $avance=$suma_total==0?0:round(($suma_total/$ncarrera->creditos_totales)*100,2);
                        $avance= min($avance, 100);
                    @endphp
                    {{$avance."%"}}
                </td>
                <td align="center">
                    @php
                        $prom_tot=($j-1)==0?0:round($calificaciones_totales/($j-1),2);
                    @endphp
                    {{$prom_tot}}
                </td>
            </tr>
            </thead>
        </table>
    </x-additional>
@stop
