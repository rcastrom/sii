@extends('adminlte::page')

@section('title', 'Alumnos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>Del siguiente listado, seleccione el aula que desea tener mayores detalles</p>
        <form action="{{route('dep_aula')}}" method="post" class="form-inline" role="form">
            @csrf
            <div class="form-group">
                <label for="salon">Buscar por salón</label>
                <select name="salon" id="salon" class="form-control" required>
                    <option value="" selected>--Seleccione--</option>
                    @foreach($aulas as $salon)
                        <option value="{{$salon->aula}}">{{$salon->aula}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="periodo" class="col-form-label">Período</label>
                <select name="periodo" id="periodo" class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}"{{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
