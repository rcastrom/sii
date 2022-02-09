@extends('adminlte::page')

@section('title', 'Grupos')

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.jqueryui.min.css">
@stop

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Carrera {{$ncarrera->nombre_reducido}} Reticula {{$ncarrera->reticula}}</h5>
        Del listado siguiente, seleccione el grupo a ser creado
        <table id="materias" class="display responsive nowrap">
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
@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.jqueryui.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.jqueryui.min.js"></script>
    <script>
        $('#materias').DataTable({});
    </script>
@stop
