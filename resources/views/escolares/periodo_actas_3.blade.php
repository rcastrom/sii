@extends('adminlte::page')

@section('title', 'Actas')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Actas del período {{$nperiodo->identificacion_corta}}</h4>
        <br>
        <h5 class="card-title">Docente {{$ndocente->apellidos_empleado}} {{$ndocente->nombre_empleado}}</h5>
        <br>
        @foreach($grupos as $grupo)
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    {{$grupo->nombre_abreviado_materia}}
                </div>
                <div class="col-sm-3 col-md-3">
                    Grupo {{$grupo->grupo}}
                </div>
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-wrench"></i>
                    <a href="/escolares/actas/modificar/{{base64_encode($periodo)}}/{{base64_encode($docente)}}/{{base64_encode($grupo->materia)}}/{{base64_encode($grupo->grupo)}}" title="Modificar">
                        Modificar</a>
                </div>
                <div class="col-sm-3 col-md-3">
                    <i class="fas fa-print"></i>
                    <a href="/escolares/actas/imprimir/{{$periodo}}/{{$docente}}/{{$grupo->materia}}/{{$grupo->grupo}}" title="Modificar">
                        Imprimir</a>
                </div>
            </div>
        @endforeach
    </x-information>
@stop
