@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css"/>
@stop

@section('title', 'Plazas')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>{{$encabezado2}}</h3>
        Emplee esta sección para llevar a cabo la consulta de plazas del personal
        <table id="plazas" class="table table-striped data-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Diagonal</th>
                    <th>Hrs</th>
                    <th>Personal</th>
                    <th>Efectos</th>
                    <th>Más información</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plazas as $plaza)
                    <tr data-id="{{$plaza->id}}">
                        <td>{{ $plaza->categoria }}</td>
                        <td>{{ $plaza->diagonal }}</td>
                        <td>{{ $plaza->horas }}</td>
                        <td>{{ $plaza->apellidos_empleado.' '.$plaza->nombre_empleado }}</td>
                        <td>{{ $plaza->efectos_iniciales.'-'.$plaza->efectos_finales }}</td>
                        <td><a href="{{route('personalPlaza.edit',$plaza->id)}}"><i class="fas fa-edit"></i>Editar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-information>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#plazas').DataTable({
                "language":{
                    "search":     "Buscar",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info":       "Mostrando página _PAGE_ de _PAGES_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Último"
                    }
                }
            });
        });
    </script>
@stop
