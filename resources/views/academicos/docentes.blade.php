@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Del siguiente listado, seleccione al docente a quien le asignará actividades de
        apoyo a la administración, consulta de horario o alguna otra actividad</p>
        <p>Emplee ésta sección si desea llevar a cabo su impresión de horarios</p>
        <form action="{{route('academicos.personal')}}" method="post" class="form-inline" role="form">
            @csrf
            <div class="form-group">
                <label for="docente">Buscar por docente</label>
                <select name="docente" id="docente" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($maestros as $maestro)
                        <option value="{{$maestro->id}}">{{$maestro->apellidos_empleado.' '.$maestro->nombre_empleado}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="periodo" class="col-form-label">Período</label>
                <select name="periodo" id="periodo" class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}"{{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
    <x-alert>
        @php
            $mensaje="Si desea llevar a cabo la asignación de carga académica, debe emplear la sección de periodos -> grupos existentes";
        @endphp
        @slot('mensaje',$mensaje)
    </x-alert>
@stop
