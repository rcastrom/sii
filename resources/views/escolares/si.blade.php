@extends('adminlte::page')

@section('title', 'Proceso terminado')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h1>Proceso terminado</h1>
        <p class="card-text">{{ $mensaje }}</p>
    </x-information>
@stop
