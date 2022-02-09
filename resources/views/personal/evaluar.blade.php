@extends('adminlte::page')

@section('title', 'Cal final')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4><br>
        <h5 class="card-title">Materia {{$nombre_mat->nombre_completo_materia}} Grupo {{$grupo}}</h5><br>
        <hr>
        <form name="calificaciones" method="post" action="{{route('personal_calificar')}}" role="form" class="form">
            @csrf
            @foreach($inscritos as $alumnos)
                <div class="form-group row">
                    <label for="{{$alumnos->no_de_control}}" class="col-sm-4 col-md-7 col-form-label">
                        {{$alumnos->no_de_control}}
                        {{$alumnos->apellido_paterno}} {{$alumnos->apellido_materno}} {{$alumnos->nombre_alumno}}
                    </label>
                    <input type="number" id="{{$materia."_".$alumnos->no_de_control}}"
                           name="{{$materia."_".$alumnos->no_de_control}}" value="0" class="col-sm-4 col-md-2 form-control">
                    <select name="{{"op_".$alumnos->no_de_control}}" id="{{"op_".$alumnos->no_de_control}}"
                            class="col-sm-4 col-md-3 form-control">
                        <option value="1" selected>Oportunidad 1</option>
                        <option value="2" >Oportunidad 2</option>
                    </select>
                </div>
            @endforeach
            <input type="hidden" name="materia" value="{{base64_encode($materia)}}">
            <input type="hidden" name="grupo" value="{{base64_encode($grupo)}}">
            <input type="hidden" name="periodo" value="{{base64_encode($nperiodo->periodo)}}">
            <div class="row">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
