@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4><br>
        <form action="{{route('personal.consulta_calificaciones')}}" method="post" class="form">
            @csrf
            <div class="form-group">
                <label for="materia" class="form-label">Seleccione la materia por consultar</label>
                <select name="materia" id="materia" required class="form-control">
                    <option value="" selected>--Seleccione la materia--</option>
                    @foreach($materias as $materia)
                        <option value="{{$materia->materia."_".$materia->grupo}}">{{$materia->nombre_completo_materia}} Gpo ({{$materia->grupo}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
