@extends('adminlte::page')

@section('title', 'Inicio')

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.jqueryui.min.css">
@stop

@section('content_header')
    <h1>Jefaturas académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Carrera {{$ncarrera->nombre_reducido}} Reticula {{$ncarrera->reticula}}</h5>
        <div class="card-body">
            Del listado siguiente, seleccione el grupo para obtener mayor información.
            <table id="materias" class="display responsive nowrap">
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
