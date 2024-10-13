@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-body">
            <h4>Docente: {{$personal->apellidos_empleado}} {{$personal->nombre_empleado}}</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('academicos.modobservaciones')}}" method="post" role="form">
                @csrf
                <label for="obs">Observación para el horario</label>
                <textarea name="obs" id="obs" cols="30" rows="10" class="form-control" required
                          onchange="this.value=this.value.toUpperCase();">{{$obs->observaciones}}</textarea>
                <button type="submit" class="btn btn-primary">Continuar</button>
                <input type="hidden" name="id" value="{{$id}}">
            </form>
        </div>
    </x-information>
@stop
