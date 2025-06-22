@extends('adminlte::page')

@section('title', 'Inicio')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css">
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
                        <td>Materia: {{$grupos->nombre_abreviado_materia}} Cve: {{$grupos->materia}}</td>
                        <td>{{$grupos->grupo}}</td>
                        <td>{{$grupos->paralelo_de}}</td>
                        <td>{{$grupos->alumnos_inscritos}}</td>
                        <td><i class="far fa-question-circle"></i>
                            <a href="{{route('academicos.info',['periodo'=>$periodo,'materia'=>$grupos->materia,'gpo'=>$grupos->grupo])}}">Mayor información</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-information>
@stop
@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>
    <script>
        new DataTable('#materias',{
            responsive:true,
            layout:{
                topStart:{
                    buttons:['copy','csv','excel','pdf','print']
                },
            },
        });
    </script>
@stop
