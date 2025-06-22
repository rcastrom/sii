@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Seleccione del siguiente recuadro, el período de búsqueda para generar la ficha
        del aspirante a ingresar
        <form action="{{route('escolares_aspirantes.listado')}}" method="post">
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
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>

@stop
