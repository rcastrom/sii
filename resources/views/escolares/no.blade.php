@extends('adminlte::page')

@section('title', 'Error')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p class="card-text">{{ $mensaje }}</p>
    </x-information>
@stop
