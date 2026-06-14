@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Proceso terminado</div>
                        <div class="card-body">
                            <h3>Panel administrativo</h3>
                            <p class="card-text">{{ $mensaje }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop

