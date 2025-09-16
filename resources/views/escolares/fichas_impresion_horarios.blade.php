@extends('adminlte::page')

@section('title', 'Aspirantes')


@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <p>Realice la impresión de los horarios correspondientes</p>
                    <ul class="list-group">
                        @foreach($controles as $control)
                            <li class="list-group-item">Impresión para {{$control}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </x-information>

@stop

