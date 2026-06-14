@extends('adminlte::page')

@section('title', 'Alta Dirección')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Cargo</th>
                            <th scope="col">Asignado a</th>
                            <th colspan="2" scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($datos as $dato)
                        <tr>
                            <td>{{$dato->descripcion_area}}</td>
                            <td>{{$dato->apellidos_empleado.' '.$dato->nombre_empleado}}</td>
                            <td>
                                <a href="{{route('listado.edit',$dato->id)}}"><i class="fas fa-edit"></i>Editar</a>
                            </td>
                            <td>
                                <a href="{{route('listado.show',$dato->id)}}"><i class="fa fa-trash"></i>Borrar</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4">La acción de "Borrar" emplearla
                                cuando una jefatura no tendrá persona asignada</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Asignación')
        <div class="row">
            <ul>
                <li><a href="{{route('listado.create')}}">Nueva asignación a cargo</a></li>
            </ul>
        </div>
    </x-additional>
@stop
