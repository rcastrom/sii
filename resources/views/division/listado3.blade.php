@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Carrera {{$ncarrera->nombre_reducido}} Reticula {{$ncarrera->reticula}}</h5>
        Del listado siguiente, seleccione el grupo a ser creado
        <table class="table table-responsive">
            <thead class="thead-light">
            <tr>
                <th>Semestre</th>
                <th>Cve materia</th>
                <th>Materia</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listado as $grupos)
                <tr>
                    <td>{{$grupos->semestre_reticula}}</td>
                    <td>{{$grupos->mater}}</td>
                    <td>{{$grupos->nombre_abreviado_materia}}</td>
                    <td><i class="far fa-check-circle"></i>
                        <a href="/division/grupos/creacion/{{$periodo}}/{{$grupos->mater}}/{{trim($carrera)}}/{{$ret}}"
                           title="Alta de grupo">Crear</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-information>
@stop
