@extends('adminlte::page')

@section('title', 'Actas')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Registro de actas del período {{$nperiodo->identificacion_corta}}</h4>
        <br>
        <h5 class="card-title">Docente {{$ndocente->apellidos_empleado}} {{$ndocente->nombre_empleado}}</h5>
        <br>
        <form action="{{route('escolares.registro4')}}" method="post" role="form">
            @csrf
            @foreach($grupos as $grupo)
                <div class="form-group">
                    <label for="{{$grupo->materia."_".$grupo->grupo}}">
                        {{$grupo->nombre_abreviado_materia}}/ Gpo {{$grupo->grupo}}
                    </label>
                    <select name="{{$grupo->materia."_".$grupo->grupo}}" id="{{$grupo->materia."_".$grupo->grupo}}"
                            required class="form-control">
                        @if($grupo->entrego==false)
                            <option value="0" selected>--Sin entregar--</option>
                            <option value="1">Entregada</option>
                        @else
                            <option value="0">--Sin entregar--</option>
                            <option value="1" selected>Entregada</option>
                        @endif
                    </select>
                </div>
            @endforeach
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <input type="hidden" name="docente" value="{{$docente}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

