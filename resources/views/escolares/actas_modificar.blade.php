@extends('adminlte::page')

@section('title', 'Actas')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5 class="card-title">Período {{$nperiodo->identificacion_corta}}</h5><br>
        <h5 class="card-title">Docente {{$ndocente->apellidos_empleado}} {{$ndocente->nombre_empleado}}</h5>
        <br>
        <h5 class="card-title">Materia {{$nmateria->nombre_abreviado_materia}} Grupo {{$grupo}}</h5>
        <br><br>
        <form action="{{route('escolares.actas_upd')}}" method="post" role="form">
            @csrf
            @foreach($alumnos as $alumno)
                <div class="form-group row">
                    <label for="{{$materia.'_'.$grupo.'_'.$alumno->no_de_control}}" class="col-sm-6 col-form-label">
                        {{$alumno->no_de_control}} {{$alumno->apellido_paterno}} {{$alumno->apellido_materno}} {{$alumno->nombre_alumno}}
                    </label >
                    <div class="col-sm-3">
                        <input type="number" class="form-control" name="{{$materia.'_'.$grupo.'_'.$alumno->no_de_control}}" value="{{$alumno->calificacion}}">
                    </div>
                    <div class="col-sm-3">
                        <select name="{{'op_'.$alumno->no_de_control}}" id="{{'op_'.$alumno->no_de_control}}" class="form-control">
                            @if($alumno->plan_de_estudios==3)
                                @foreach($tipo_3 as $t3)
                                    <option value="{{$t3->tipo_evaluacion}}"{{$t3->tipo_evaluacion==$alumno->tipo_evaluacion?' selected':''}}>{{$t3->descripcion_corta_evaluacion}}</option>
                                @endforeach
                            @elseif($alumno->plan_de_estudios==4)
                                @foreach($tipo_4 as $t4)
                                    <option value="{{$t4->tipo_evaluacion}}"{{$t4->tipo_evaluacion==$alumno->tipo_evaluacion?' selected':''}}>{{$t4->descripcion_corta_evaluacion}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            @endforeach
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <input type="hidden" name="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" value="{{$grupo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
        </div>
        <x-aviso>
            Actualizar el acta también actualiza al kárdex de manera automática solamente si la calificación
            de los estudiantes ya estaba registrada
        </x-aviso>
    </x-information>
@stop


