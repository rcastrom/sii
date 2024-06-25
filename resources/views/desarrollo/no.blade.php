@extends('adminlte::page')

@section('title', 'Error')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p class="card-text">{{ $mensaje }}</p>
    </x-information>
@stop
