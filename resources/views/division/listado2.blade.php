@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Carrera {{$ncarrera->nombre_reducido}} Reticula {{$ncarrera->reticula}}</h5>
        <div class="card-body">
            Del listado siguiente, seleccione el grupo para obtener mayor información.
            <table class="table table-responsive">
                <thead class="thead-light">
                <tr>
                    <th>Semestre</th>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Paralelo de</th>
                    <th>Inscritos</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($listado as $grupos)
                    <tr>
                        <td>{{$grupos->semestre_reticula}}</td>
                        <td>Materia: {{$grupos->nombre_abreviado_materia}} Cve: {{$grupos->mater}}</td>
                        <td>{{$grupos->grupo}}</td>
                        <td>{{$grupos->paralelo_de}}</td>
                        <td>{{$grupos->alumnos_inscritos}}</td>
                        <td><i class="far fa-question-circle"></i>
                            <a href="/division/grupos/info/{{$periodo}}/{{$grupos->mater}}/{{$grupos->grupo}}"
                               title="Obtener información">Mayor información</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-information>
@stop
