@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
@stop

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <table id="population" class="table table-striped display ">
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
                                    <a href="{{route('ficha.show',['ficha'=>$dato->aspirante_id])}}"><i class="fa fa-pencil-alt"></i>Editar</a>
                                    <a href="{{route('escolares.imprimir_ficha',['identificador'=>$dato->aspirante_id])}}"
                                       target="_blank"><i class="fa fa-print"></i>Imprimir</a>
                                    <a href="{{route('ficha.edit',['ficha'=>$dato->aspirante_id])}}"><i class="fa fa-file"></i>Documentos</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            En la sección de editar, es donde podrá validar la ficha.<br>
            En la sección de documentos, es donde podrá consultar o modificar, los
            documentos que el aspirante ha entregado o falta por entregar
        </div>
    </x-information>

@stop


@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#population',{
           responsive:true,
        });
    </script>
@stop
