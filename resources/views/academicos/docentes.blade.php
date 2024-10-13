@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Del siguiente listado, seleccione el aula que desea tener mayores detalles</p>
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
@stop
