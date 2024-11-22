@extends('adminlte::page')

@section('title', 'Evaluación docente')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card-header">Periodo {{$nperiodo->identificacion_corta}}</div>
                </div>
                <div class="col-6">
                    <div class="card-header">Carrera {{$nombre_carrera->nombre_reducido}}</div>
                </div>
                <div class="col-6">
                    <div class="card-header">Retícula {{$reticula}}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">Materias de la carrera</div>
                <div class="col-4">Docentes que imparten a la carrera</div>
                <div class="col-4">No. de alumnos</div>
            </div>
            <div class="row">
                <div class="col-2">Activas</div>
                <div class="col-2">Evaluadas</div>
                <div class="col-2">Activos</div>
                <div class="col-2">Evaluados</div>
                <div class="col-2">Inscritos</div>
                <div class="col-2">Evaluaron</div>
            </div>
            <div class="row">
                <div class="col-2">{{$materias_activas}}</div>
                <div class="col-2">{{$materias_evaluadas}}</div>
                <div class="col-2">{{$docentes_activos}}</div>
                <div class="col-2">{{$docentes_evaluados}}</div>
                <div class="col-2">{{$alumnos_activos}}</div>
                <div class="col-2">{{$alumnos_evaluados}}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-header">Aspectos a evaluar</div>
            <div class="row">
                <div class="col">Aspectos evaluados</div>
                <div class="col">Puntaje</div>
                <div class="col">Calificación</div>
            </div>
            @foreach($resultados as $key=>$value)
                <div class="row">
                    <div class="col">{{$value["aspecto"].") ".$value["descripcion"]}}</div>
                    <div class="col">{{$value["porcentaje"]}}</div>
                    <div class="col">{{$value["calificacion"]}}</div>
                </div>
            @endforeach
            <h5>Promedio General</h5>
            <div class="row">
                <div class="col-sm-6 col-md-4">{{$promedio}}</div>
                <div class="col-sm-6 col-md-8">{{$cal}}</div>
            </div>
        </div>
        <div class="card">
            <img src="{{route('academicos.grafica_evaluacion_docente_carrera',['periodo'=>$periodo,'carrera'=>$carrera,
            'reticula'=>$reticula,'promedio'=>$promedio])}}" alt="">
        </div>
    </x-information>
@stop
