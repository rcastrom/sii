@extends('adminlte::page')

@section('title', 'Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <p class="card-text">Número de control: {{ $control }}</p>
        <p class="card-text">{{ $ncarrera->nombre_carrera }} Ret {{ $alumno->reticula }}</p>
        <p class="card-text">Semestre {{ $alumno->semestre }}</p>
        <p class="card-text">Estatus actual: {{ $estatus->descripcion }}</p>
    </x-information>
    <x-additional>
        @slot('header','Kardex de alumno')
            @php
                $suma_total=0; $calificaciones_totales=0; $j=1;
                $tipos_mat=array("R1","R2","RO","RP");
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
                        $avance1=$suma_total==0?0:round(($suma_total/$ncarrera->creditos_totales)*100,2);
                        $avance = ($avance1>=100)?100:$avance1;
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
    <x-information :encabezado="$encabezado2">
        <form method="post" action="{{route('escolares.accion_kardex')}}" class="form-inline" role="form">
            @csrf
            <legend>Seleccione una opción</legend>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion" class="sr-only">Acción</label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Agregar materia</option>
                        <option value="2">Modificar materia</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
            <input type="hidden" name="control" id="control" value="{{ $control }}">
        </form>
    </x-information>
    <x-information :encabezado="$encabezado3">
        <form action="{{route('escolares.imprimirkardex')}}" target="_blank" method="post" class="form-inline" role="form">
            @csrf
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <input type="hidden" name="control" id="control" value="{{ $control }}">
                    <button type="submit" class="btn btn-primary">Imprimir</button>
                </div>
            </div>
        </form>
    </x-information>
    <x-aviso>
        <p>Para modificar o eliminar una materia, el sistema le solicitará indique primeramente el período
            de la misma</p>
    </x-aviso>
@stop

