@extends('adminlte::page')

@section('title', 'Aspirantes')


@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-12">
                <div class="card-body">
                    <p>Seleccione del siguiente listado, los aspirantes a quienes
                        se les generará el número de control correspondiente</p>
                    <h5>Carrera: {{$datos_carrera->nombre_carrera}}</h5>
                    <form action="{{route('escolares.aspirantes_seleccionados')}}" method="post">
                        @csrf
                        <ul class="list-group">
                            @foreach($aceptados as $aceptado)
                                <li class="list-group-item">
                                    <input type="checkbox" name="aceptados[]" id="{{$aceptado->id}}" value="{{$aceptado->id}}" class="form-check-input me-1">
                                    <label for="{{$aceptado->id}}" class="form-check-label">
                                        Ficha {{$aceptado->ficha}} {{$aceptado->apellido_paterno.' '.$aceptado->apellido_materno.' '.$aceptado->nombre_aspirante}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="periodo" value="{{$periodo}}">
                        <input type="hidden" name="reticula" value="{{$datos_carrera->reticula}}">
                        <div class="form-group mt-3">
                            <input type="submit" value="Continuar" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-information>

@stop

