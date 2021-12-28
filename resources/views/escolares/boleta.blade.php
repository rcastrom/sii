@extends('adminlte::page')

@section('title', 'Boleta')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
    </x-information>
    <x-additional>
        @slot('header','Boleta')
        <h6>Período: {{$nombre_periodo->identificacion_larga}}</h6>
        @php
            $tipos_mat=array("O2","R1","R2","RO","RP","2");
            $i=1;
            $suma_creditos=0;
            $promedio_semestre=0;
            $suma_semestre=0;
            $cal_sem=0;
        @endphp
        <table class="table table-responsive table-bordered">
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
            @foreach($cal_periodo as $data)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$data->nombre_completo_materia}}</td>
                    <td>{{$data->calificacion<60?"NA":($data->tipo_evaluacion=='AC'?'AC':$data->calificacion)}}</td>
                    <td>{{$data->descripcion_corta_evaluacion}}</td>
                    <td>{{$data->creditos_materia}}</td>
                    @if(($data->calificacion < 70 && in_array($data->tipo_evaluacion,$tipos_mat)) || ($data->calificacion < 70 && $data->tipo_evaluacion == 'EA'))
                        @if($alumno->plan_de_estudios==3||$alumno->plan_de_estudios==4)
                            <td>A curso especial</td>
                        @else
                            <td></td>
                        @endif
                    @endif
                </tr>
                @if($data->calificacion>=70||($data->tipo_evaluacion=='AC'))
                    @php
                        $suma_creditos+=$data->creditos_materia;
                        $cal_sem+=$data->calificacion;
                    @endphp
                @endif
                @php
                    $suma_semestre+=$data->creditos_materia;
                    $i++;
                @endphp
            @endforeach
            @php($promedio=round($cal_sem/($i-1),2))
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
    </x-additional>
    <x-additional>
        @slot('header','Datos Adicionales')
        <form method="post" action="{{route('escolares.imprimir boleta')}}" class="form-inline" role="form" target="_blank">
            @csrf
            <legend>Seleccione una opción</legend>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion" class="sr-only">Acción</label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Imprimir</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
            <input type="hidden" name="control" id="control" value="{{ $alumno->no_de_control }}">
            <input type="hidden" name="periodo" id="periodo" value="{{ $periodo }}">
        </form>
    </x-additional>
@stop
