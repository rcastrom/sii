@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <form method="post" action="{{route('escolares.accion')}}" class="form-inline" role="form">
                @csrf
                <legend>Acción a realizar</legend>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label for="control">Seleccione al estudiante</label>
                        <select name="control" id="control" required class="form-control">
                            <option value="" selected>--Seleccione</option>
                            @foreach($arroja as $datos)
                                <option value='{{ "$datos->no_de_control" }}'>
                                    {{ strval($datos->no_de_control)." ".$datos->apellido_paterno.' '.$datos->apellido_materno.
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
                            <option value="3">Constancia de Estudios</option>
                            <option value="4">Boleta</option>
                            <option value="5">Horario</option>
                            <option value="6">Cambiar estatus</option>
                            <option value="7">Validar reinscripción</option>
                            <option value="8">Asignar especialidad</option>
                            <option value="9">Cambio carrera</option>
                            <option value="10">Eliminar número de control</option>
                            <option value="11">Baja temporal o definitiva</option>
                            <option value="12">Asignación de NSS</option>
                            <option value="13">Acreditar complementaria</option>
                            <option value="14">Liberación idioma extranjero</option>
                            <option value="15">Certificado</option>
                            <option value="16">Modificar datos estudiante</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6">
                        <label for="periodo">Periodo</label>
                        <select name="periodo" id="periodo" required class="form-control">
                            @foreach($periodos as $pers)
                                @if($periodo[0]->periodo==$pers->periodo)
                                    <option value="{{$pers->periodo}}" selected>{{$pers->identificacion_corta}}</option>
                                @else
                                    <option value="{{$pers->periodo}}">{{$pers->identificacion_corta}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        El período puede no ser necesario dependiendo de la acción a realizar
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
    </x-information>

@stop
