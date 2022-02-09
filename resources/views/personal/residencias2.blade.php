@extends('adminlte::page')

@section('title', 'Residencias')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>No</th>
                <th>Control</th>
                <th>Nombre</th>
            </tr>
            </thead>
            <tbody>
            @php
                $i=1;
            @endphp
            @foreach($quienes as $quien)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$quien->no_de_control}}</td>
                    <td>{{$quien->apellido_paterno}} {{$quien->apellido_materno}} {{$quien->nombre_alumno}}</td>
                    @if(is_null($quien->calificacion))
                    <td><i class="fas fa-sort-numeric-up"></i>
                        <a href="/personal/periodo/residencia/evaluar/{{base64_encode($per_residencias)}}/{{base64_encode($quien->materia)}}/{{base64_encode($quien->grupo)}}/{{base64_encode($quien->no_de_control)}}" title="Calificaciones">Evaluar</a></td>
                    @else
                    <td></td>
                    @endif
                    <td><i class="fas fa-print"></i>
                        <a href="/personal/periodo/residencia/acta/{{$per_residencias}}/{{$quien->materia}}/{{$quien->grupo}}" title="Acta">Imprimir acta final</a></td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
            </tbody>
        </table>
    </x-information>
@stop
