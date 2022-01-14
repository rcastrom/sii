@extends('adminlte::page')

@section('title', 'Reinscripción')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form action="{{route('escolares.accion-reinscripcion')}}" method="post" role="form">
            @csrf
            <div class="form-group row">
                <label for="periodo" class="col-sm-4 col-form-label">Período</label>
                <div class="col-sm-8">
                    <select name="periodo" id="periodo" class="form-control">
                        @foreach($periodos as $per)
                            <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual[0]->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="carrera" class="col-sm-4 col-form-label">Carrera</label>
                <div class="col-sm-8">
                    <select name="carrera" id="carrera" class="form-control" required>
                        <option value="" selected>--Seleccione--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{$carrera->carrera}}">{{$carrera->nombre_reducido}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="accion" class="col-sm-4 col-form-label">Acción</label>
                <div class="col-sm-8">
                    <select name="accion" id="accion" class="form-control" required>
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Establecer fechas de inscripción</option>
                        <option value="2">Generar lista inscripción</option>
                        <option value="3">Imprimir lista orden inscripción</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </div>
        </form>
    </x-information>
@stop
