@extends('adminlte::page')

@section('title', 'Eliminar materia en Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3 class="card-title">{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</h3>
        <br>
        <h4 class="card-title">Número de control {{$alumno->no_de_control}}</h4><br>
        <div class="form-group">
            <form action="{{route('kardex.destroy',$kardex->id)}}" method="post" role="form">
                @csrf
                @method('DELETE')
                Está a punto de eliminar el registro de:
                <ul>
                    <li>Materia: {{$materia->nombre_completo_materia}}</li>
                    <li>Periodo: {{$periodo->identificacion_larga}}</li>
                    <li>Clave: {{$kardex->materia}}</li>
                    <li>Calificación: {{$kardex->calificacion}}</li>
                </ul>
                <button type="submit" class="btn btn-primary">¿Desea continuar?</button>
            </form>
        </div>
    </x-information>
@stop
