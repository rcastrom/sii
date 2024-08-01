@extends('adminlte::page')

@section('title', 'Mov en Kardex')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3 class="card-title">{{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}</h3>
        <br>
        <h4 class="card-title">Número de control {{$alumno->no_de_control}}</h4><br>
        <form action="{{route('kardex.update',$kardex->id)}}" method="post" role="form">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    Materia
                </div>
                <div class="col-sm-6 col-md-6">
                    {{$kardex->materia}} / {{$materia->nombre_abreviado_materia}}
                </div>
            </div>
            <div class="form-group row">
                <label for="calificacion" class="col-sm-4 col-form-label">Calificación</label>
                <div class="col-sm-8">
                    <input type="number" value="{{$kardex->calificacion}}" required
                           name="calificacion" id="calificacion" max="100" min="0" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="periodo" class="col-sm-4 col-form-label">Período</label>
                <div class="col-sm-8">
                    <select name="periodo" id="periodo" required class="form-control">
                        @foreach($periodos as $periodo)
                            <option value="{{$periodo->periodo}}" {{$periodo->periodo==$kardex->periodo?' selected':''}}>{{$periodo->identificacion_corta}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo_evaluacion" class="col-sm-4 col-form-label">Tipo de evaluación</label>
                <div class="col-sm-8">
                    <select name="tipo_evaluacion" id="tipo_evaluacion" required class="form-control">
                        @foreach($tipos as $tipo)
                            <option value="{{$tipo->tipo_evaluacion}}" {{$tipo->tipo_evaluacion==$kardex->tipo_evaluacion?' selected':''}}>({{$tipo->tipo_evaluacion}}) {{$tipo->descripcion_corta_evaluacion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
    <x-aviso>
        Una materia por ser acreditada como equivalencia, revalidación o convalidación,
        debe asignársele una calificación de 60 e indicar el tipo de acreditación que le
        corresponda.
    </x-aviso>
@stop
