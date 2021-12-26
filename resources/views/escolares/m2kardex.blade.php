@extends('adminlte::page')

@section('title', 'Mov en Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</h4>
        <br>
        <h4 class="card-title">Número de control {{$control}}</h4><br>
        <h4 class="card-title">Período {{$nperiodo->identificacion_corta}}</h4><br>
        <hr>
        @foreach($mat as $materias)
            <div class="row">
                <div class="col-sm-4 col-md-4">
                    {{$materias->nombre_abreviado_materia}}
                </div>
                <div class="col-sm-3 col-md-2">
                    {{$materias->calificacion}}
                </div>
                <div class="col-sm-3 col-md-2">
                    {{$materias->tipo_evaluacion}}/{{$materias->descripcion_corta_evaluacion}}
                </div>
                <div class="col-sm-2 col-md-4">
                    <i class="fas fa-wrench"></i>
                    <a href="/escolares/alumnos/modificar/{{$periodo}}/{{$control}}/{{$materias->materia}}" title="Modificar">
                        Modificar</a>
                    <i class="fas fa-trash-alt"></i>
                    <a href="/escolares/alumnos/eliminar/{{$periodo}}/{{$control}}/{{$materias->materia}}" title="Eliminar">
                        Eliminar</a>
                </div>
            </div>
        @endforeach
    </x-information>
@stop
