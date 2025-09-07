@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Seleccione el periodo para crear el número de control de nuevo ingreso.
        <form action="{{route('escolares.listado_aceptados')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="periodo" class="form-label"></label>
                <select name="periodo" id="periodo" class="form-control" required>
                    @foreach($periodos as $periodo)
                        <option value="{{$periodo->periodo}}"{{$periodo->periodo==$periodo_ficha?' selected':''}}>
                            {{trim($periodo->identificacion_larga)}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="carrera" class="form-label"></label>
                <select name="carrera" id="carrera" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($carreras as $carrera)
                        <option value="{{$carrera->carrera}}">
                            {{$carrera->nombre_carrera}}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>

@stop
