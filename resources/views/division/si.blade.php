@extends('adminlte::page')

@section('title', 'Proceso terminado')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>Proceso terminado</h3>
        <p class="card-text">{{ $mensaje }}</p>
    </x-information>
@stop
