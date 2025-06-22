@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
@stop

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <table id="population" class="display responsive nowrap">
            <thead>
                <tr>
                    <th>Ficha</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos as $dato)
                    <tr>
                        <td>{{$dato->ficha}}</td>
                        <td>{{$dato->apellido_paterno_aspirante}}</td>
                        <td>{{$dato->apellido_materno_aspirante}}</td>
                        <td>{{$dato->nombre_aspirante}}</td>
                        <td>
                            <i class="fa fa-pencil-alt"></i>
                            <a href="{{route('ficha.show',['ficha'=>$dato->aspirante_id])}}">Editar</a>
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            <a href="{{route('ficha.destroy',['ficha'=>$dato->aspirante_id])}}">Eliminar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        <tfoot>
            <tr>
                <td colspan="4">En la sección de editar, es donde podrá validar la ficha</td>
            </tr>
        </tfoot>
        </table>
    </x-information>

@stop


@section('js')
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>
    <script>
        new DataTable('#population',{
           responsive:true,
        });
    </script>
@stop
