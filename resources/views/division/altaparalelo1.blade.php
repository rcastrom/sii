@extends('adminlte::page')

@section('title', 'Grupos Paralelos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <p>En base al listado de carreras - retículas, seleccione para la creación del grupos paralelos</p>
        <form action="{{route('dep_paralelo2')}}" method="post" role="form">
            @csrf
            <div class="mb-4">
                <label for="carrerao" class="col-sm-6 col-form-label">Seleccione la carrera ORIGEN</label>
                <div class="col-sm-6">
                    <select name="carrerao" id="carrerao" required class="form-control">
                        <option value="" selected>--ORIGEN--</option>
                        @foreach($carrera_origen as $carrera)
                            <option value="{{$carrera->carrera.'_'.$carrera->reticula}}">{{$carrera->nombre_reducido.' RET('.$carrera->reticula.')'}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="carrerap" class="col-sm-6 col-form-label">Seleccione la carrera PARALELA</label>
                <div class="col-sm-6">
                    <select name="carrerap" id="carrerap" required class="form-control">
                        <option value="" selected>--PARALELA--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{$carrera->carrera.'_'.$carrera->reticula}}">{{$carrera->nombre_reducido.' RET('.$carrera->reticula.')'}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label for="periodo" class="col-sm-6 col-form-label">Periodo de alta</label>
                <div class="col-sm-6">
                    <select name="periodo" id="periodo" required class="form-control">
                        @foreach($periodos as $per)
                            <option value="{{$per->periodo}}"{{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
