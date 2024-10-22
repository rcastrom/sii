@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Asignación, consulta e impresión de horario para personal no docente</p>
        <form action="{{route('nodocente.alta')}}" method="post" class="form" role="form">
            @csrf
            <div class="form-group row">
                <label for="admin" class="col-sm-6 col-form-label">Buscar por personal </label>
                <div class="col-sm-6">
                    <select name="admin" id="admin" class="form-control" required>
                        <option value="" selected>--Seleccione--</option>
                        @foreach($administrativos as $administrativo)
                            <option value="{{$administrativo->id}}">{{$administrativo->apellidos_empleado.' '.$administrativo->nombre_empleado}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="periodo" class="col-sm-6 col-form-label">Período</label>
                <div class="col-sm-6">
                    <select name="periodo" id="periodo" class="form-control">
                        @foreach($periodos as $per)
                            <option value="{{$per->periodo}}"{{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="accion" class="col-sm-6 col-form-label">Acción por realizar</label>
                <div class="col-sm-6">
                    <select name="accion" id="accion" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Consulta</option>
                        <option value="2">Alta</option>
                        <option value="3">Impresión</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
