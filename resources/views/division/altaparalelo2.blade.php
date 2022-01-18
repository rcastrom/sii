@extends('adminlte::page')

@section('title', 'Grupos Paralelos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('dep_paralelo3')}}" method="post" role="form">
            @csrf
            <div class="row mb-4">
                <label for="carrerao" class="col-sm-6 col-form-label">Seleccione la materia ORIGEN</label>
                <div class="col-sm-6">
                    <select name="mat_o" id="mat_o" required class="form-control">
                        <option value="" selected>--ORIGEN--</option>
                        @foreach($listado_o as $origen)
                            <option value="{{$origen->mater."_".$origen->grupo}}">({{$origen->mater}}) {{$origen->nombre_abreviado_materia}} Gpo {{$origen->grupo}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <diw class="row mb-4">
                <label for="matp" class="col-sm-6 col-form-label">Seleccione la materia PARALELA</label>
                <div class="col-sm-6">
                    <select name="matp" id="matp" required class="form-control">
                        <option value="" selected>--PARALELA--</option>
                        @foreach($listado_p as $destino)
                            <option value="{{$destino->mater}}">({{$destino->mater}}) {{$destino->nombre_abreviado_materia}}</option>
                        @endforeach
                    </select>
                </div>
            </diw>
            <div class="row mb-4">
                <label for="gpo_p" class="col-form-label col-sm-6">Grupo Paralelo</label>
                <div class="col-sm-6">
                    <input type="text" name="gpo_p" id="gpo_p" required class="form-control" maxlength="3" onchange="this.value=this.vslue.toUpperCase();">
                </div>
            </div>
            <div class="row mb-4">
                <label for="cap_n" class="col-sm-6 col-form-label">Capacidad del grupo paralelo</label>
                <div class="col-sm-6">
                    <input type="number" name="cap_n" id="cap_n" required class="form-control">
                </div>
            </div>
            <input type="hidden" name="carrera_o" id="carrera_o" value="{{$carrera_o}}">
            <input type="hidden" name="ret_o" id="ret_o" value="{{$ret_o}}">
            <input type="hidden" name="carrera_p" id="carrera_p" value="{{$carrera_p}}">
            <input type="hidden" name="ret_p" id="ret_p" value="{{$ret_p}}">
            <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
