@extends('adminlte::page')

@section('title', 'Kardex')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Módulo Estudiantes</div>
                        <div class="card-body">
                            <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
                            <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
                            <p class="card-text">{{ $nombre_carrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
                            <p class="card-text">Semestre {{ $alumno->semestre }}</p>
                            <p class="card-text">Estatus actual: {{ $estatus->descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Kardex de alumno</div>
                        <div class="card-body">
                            @php
                                $suma_total=0;
                                $calificaciones_totales=0;
                                $j=1;
                                $tipos_mat=array("O2","R1","R2","RO","RP");
                                $tipos_aprob=array('AC','RC','RU','PG');
                            @endphp
                            @foreach($calificaciones as $key=>$value)
                                @if(!empty($value))
                                    <caption>{{$nperiodos[$key]->identificacion_larga}}</caption>
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
                                            <?php
                                            $i=1;
                                            $suma_creditos=0;
                                            $promedio_semestre=0;
                                            $suma_semestre=0;
                                            $cal_sem=0;
                                            $materias=1;
                                            ?>
                                        @foreach($value as $data)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$data->nombre_completo_materia}}</td>
                                                <td>{{$data->calificacion <= 70 && in_array($data->tipo_evaluacion,$tipos_aprob)?'AC':($data->calificacion < 70?"NA":$data->calificacion)}}</td>
                                                <td>{{$data->descripcion_corta_evaluacion}}</td>
                                                <td>{{$data->creditos_materia}}</td>
                                                @if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA')){
                                                @if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4){
                                                <td>A curso especial</td>
                                                }
                                                @else{
                                                <td></td>
                                                }
                                                @endif
                                                @endif
                                            </tr>
                                                <?php
                                                if($data->calificacion>=70||in_array($data->tipo_evaluacion,$tipos_aprob)){
                                                    $suma_creditos+=$data->creditos_materia;
                                                    if(!in_array($data->tipo_evaluacion,$tipos_aprob)){
                                                        $cal_sem+=$data->calificacion;
                                                        $calificaciones_totales+=$data->calificacion;
                                                        $materias+=1;
                                                        $j++;
                                                    }
                                                    $suma_total+=$data->creditos_materia;

                                                }elseif($data->calificacion<70&&!in_array($data->tipo_evaluacion,$tipos_aprob)){
                                                    $materias+=1;
                                                }
                                                $suma_semestre+=$data->creditos_materia;
                                                $i++;
                                                ?>
                                        @endforeach
                                            <?php $promedio=($materias-1)==0?0:round($cal_sem/($materias-1),2); ?>
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
                                    <td align="center"><?php $avance=$suma_total==0?0:round(($suma_total/$nombre_carrera->creditos_totales)*100,2); ?>{{min($avance,100)."%"}}</td>
                                    <td align="center"><?php $prom_tot=($j-1)==0?0:round($calificaciones_totales/($j-1),2); ?>{{$prom_tot}}</td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Datos Adicionales</div>
                    <div class="card-body">
                        <a href="/estudiante/historial/kardex/2" target="_blank">Imprimir kardex</a>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop


