@extends('adminlte::page')

@section('title', 'Idioma Extranjero')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-title">Idioma {{$nidioma->idiomas}} Periodo {{$nperiodo->identificacion_corta}}</div>
        <table class="table table-responsive table-light table-striped">
            <thead>
            <tr>
                <th>Curso</th>
                <th>Inscritos</th>
            </tr>
            </thead>
            <tbody>
            <?php $suma=0;?>
            @foreach($info as $idiomas)
                @if($idiomas->cantidad>0)
                    <tr>
                        <td>{{$idiomas->ncurso}}</td>
                        <td>{{$idiomas->cantidad}}</td>
                        <?php $suma+=$idiomas->cantidad;?>
                    </tr>
                @endif
            @endforeach
            </tbody>
            <tfooter>
                <tr>
                    <td>Total</td>
                    <td>{{$suma}}</td>
                </tr>
            </tfooter>
        </table>
    </x-information>
@stop

