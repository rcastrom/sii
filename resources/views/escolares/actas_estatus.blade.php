@extends('adminlte::page')

@section('title', 'Status actas')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Estatus de materias del período {{$nperiodo->identificacion_corta}}</h4>
        <br>
        <p>El módulo se emplea para conocer situaciones de actas (docentes que ya
            evaluaron, que no han evaluado, entregadas)</p>
        <br>
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Docente</th>
                <th>Materia</th>
                <th>Grupo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($resultado as $data)
                <tr>
                    <td>{{$data->apellidos_empleado}} {{$data->nombre_empleado}}</td>
                    <td>{{$data->nombre_abreviado_materia}}</td>
                    <td>{{$data->materia}}/{{$data->grupo}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-information>
@stop
