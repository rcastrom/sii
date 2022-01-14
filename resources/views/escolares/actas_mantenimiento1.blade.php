@extends('adminlte::page')

@section('title', 'Status actas+')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        El módulo se emplea para conocer situaciones de actas (docentes que ya
        evaluaron, que no han evaluado, entregadas)</p>
        <form action="{{route('escolares.actas_estatus')}}" method="post" class="form" role="form">
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
                <label for="accion" class="col-form-label">Estatus de consulta</label>
                <select name="accion" id="accion" class="form-control">
                    <option value="" selected>--Seleccione--</option>
                    <option value="1">Docentes que no han capturado</option>
                    <option value="2">Docentes que ya capturaron</option>
                    <option value="3">Actas no entregadas a Escolares</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
