@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
@stop

@section('title', 'Alta Personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>Listado y consulta del personal</h3>
        Emplee esta sección no solo para realizar consultas del personal, sino para llevar a cabo modificaciones
        <table id="personal" class="table table-striped data-table">
            <thead>
                <tr>
                    <th>Num. Empleado</th>
                    <th>Nombre</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personal as $persona)
                    <tr data-id="{{$persona->id}}">
                        <td>{{ $persona->no_tarjeta }}</td>
                        <td>{{ $persona->apellido_paterno.' '.$persona->apellido_materno.' '.$persona->nombre_empleado }}</td>
                        <td><a href="/rechumanos/personal/editar/{{ base64_encode($persona->id) }}"><i class="fas fa-edit"></i>Editar</a></td>
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
            $('#personal').DataTable({
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
