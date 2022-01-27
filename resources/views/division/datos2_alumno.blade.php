@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form method="post" action="{{ route('dep.accion2') }}" class="form-inline" role="form">
            @csrf
            <legend>Acción a realizar</legend>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="control">Seleccione al estudiante</label>
                    <select name="control" id="control" required class="form-control">
                        <option value="" selected>--Seleccione</option>
                        @foreach($arroja as $datos)
                            <option value='{{ $datos->no_de_control }}'>
                                {{ $datos->no_de_control." ".$datos->apellido_paterno.' '.$datos->apellido_materno.
                                    ' '.$datos->nombre_alumno}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="accion"> Accion a realizar </label>
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Kárdex</option>
                        <option value="2">Retícula</option>
                        <option value="3">Horario</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
