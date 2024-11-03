@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>Personal Docente</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Período {{$nperiodo->identificacion_larga}}</h4><br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('personal.parciales')}}" method="post" class="form">
            @csrf
            <div class="form-group">
                <label for="materia" class="form-label">Seleccione la materia a la que
                    subirá las calificaciones parciales</label>
                <select name="materia" id="materia" required class="form-control">
                    <option value="" selected>--Seleccione la materia--</option>
                    @foreach($materias as $materia)
                        <option value="{{$materia->materia."_".$materia->grupo}}">{{$materia->nombre_completo_materia}} Gpo ({{$materia->grupo}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="unidad" class="form-label">Unidad a evaluar</label>
                    <input type="number" name="unidad" id="unidad"
                           required class="form-control" min="1" max="10">
            </div>
            <div class="form-group">
                <button type="submit" class="btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
