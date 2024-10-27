@extends('adminlte::page')

@section('title', 'Kardex')

@section('content_header')
    <h1>Estudiantes</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{route('alumnos.boleta')}}" role="form" class="form-inline">
                    @csrf
                    <div class="form-group">
                        <label for="periodo_busqueda">Indique el período a buscar</label>
                        <select name="periodo_busqueda" id="periodo_busqueda" class="form-control" required>
                            <option value="" selected>--Seleccione--</option>
                            @foreach($periodos as $periodo)
                                <option value="{{$periodo->periodo}}">{{$periodo->identificacion_corta}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </x-information>
@stop


