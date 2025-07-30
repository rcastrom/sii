@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Seleccione el periodo de búsqueda del aspirante de nuevo ingreso, así como
        el estadístico por generar.
        <form action="{{route('escolares.estadistica_fichas')}}" method="post">
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
                <label for="busqueda" class="form-label">Indique el estadístico por generar</label>
                <select name="busqueda" id="busqueda" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    <option value="1">Fichas entregadas por carrera</option>
                    <option value="2">Fichas entregadas por género</option>
                    <option value="3">Documentos que no han entregado</option>
                </select>
            </div>
            <div class="form-group">
                <p>En el caso de "Documentos que no han entregado", se generará un archivo de
                Excel en donde, si la columna le indica como "Verdadero" o "1", significa que según
                    los registros, ese documento ya lo marcó como entregado; mientras que, si le aparece
                    en blanco o "0", significa que ese es el documento faltante.
                </p>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>

@stop
