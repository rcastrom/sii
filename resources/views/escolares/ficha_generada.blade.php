@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h1>Proceso terminado</h1>
        <p class="card-text">{{ $mensaje }}</p>


        <a href="{{route('escolares.imprimir_ficha',['identificador'=>$identificador])}}" target="_blank">
            <i class="fa fa-print"></i>Imprimir ficha</a>

    </x-information>
@stop

