@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
@stop

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">

            <table id="aspirantes" class="display responsive nowrap">
                <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Apellido paterno</th>
                    <th>Apellido materno</th>
                    <th>Nombre</th>
                    <th>Carrera solicitada</th>
                    <th>Ficha</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $i=1;
                @endphp
                @foreach($listados as $listado)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$listado->apellido_paterno_aspirante}}</td>
                        <td>{{$listado->apellido_materno_aspirante}}</td>
                        <td>{{$listado->nombre_aspirante}}</td>
                        @php
                            $nombre_carrera=\Illuminate\Support\Facades\DB::table('carreras')
                        ->select('nombre_reducido')
                        ->where(['carrera'=>$listado->carrera,'ofertar'=>1])
                        ->first();
                        @endphp
                        <td>{{$nombre_carrera->nombre_reducido}}</td>
                        <td>{{$listado->ficha}}</td>
                        <td><i class="far fa-question-circle"></i>
                            <a href="{{route('desarrollo.datos_aspirante',['periodo'=>$periodo,'aspirante'=>$listado->aspirante_id])}}"
                               title="Obtener información">Mayor información</a>
                            </td>
                        @php
                            $i++;
                        @endphp
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-information>
@stop
@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <script>
        new DataTable('#aspirantes', { layout: { topStart: { buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] } } });
    </script>
@stop
