@extends('adminlte::page')

@section('title', 'Actualización de período')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form action="{{route('escolares.periodo_mod1')}}" method="post" class="form-inline" role="form">
            @csrf
            <div class="form-group">
                <label for="periodo" class="col-form-label">Período</label>
                <select name="periodo" id="periodo" class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual[0]->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
