@extends('adminlte::page')

@section('title', 'Mov en Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</h4>
        <br>
        <h4 class="card-title">Número de control {{$control}}</h4><br>
        <form action="{{route('escolares.accion_actualiza_kardex')}}" method="post" role="form">
            @csrf
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    Materia
                </div>
                <div class="col-sm-6 col-md-6">
                    {{$materia}} / {{$mat->nombre_abreviado_materia}}
                </div>
            </div>
            <div class="form-group row">
                <label for="calificacion" class="col-sm-4 col-form-label">Calificación</label>
                <div class="col-sm-8">
                    <input type="number" value="{{$mat->calificacion}}" name="calificacion" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="periodo" class="col-sm-4 col-form-label">Período</label>
                <div class="col-sm-8">
                    <select name="periodo" id="periodo" class="form-control">
                        @foreach($periodos as $per)
                            <option value="{{$per->periodo}}" {{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo_ev" class="col-sm-4 col-form-label">Tipo de evaluación</label>
                <div class="col-sm-8">
                    <select name="tipo_ev" id="tipo_ev" class="form-control">
                        @foreach($tipos as $tipo)
                            <option value="{{$tipo->tipo_evaluacion}}" {{$tipo->tipo_evaluacion==$mat->tipo_evaluacion?' selected':''}}>({{$tipo->tipo_evaluacion}}) {{$tipo->descripcion_corta_evaluacion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="control" value="{{$control}}">
            <input type="hidden" name="materia" value="{{$materia}}">
            <input type="hidden" name="periodo_o" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
