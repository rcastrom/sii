@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Ingreso de nuevo estudio cursado por personal</h4>
                <h5>{{$nombre}}</h5>
                <form action="" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select name="carrera" id="carrera" class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($carreras as $carrera)
                                <option value="{{$carrera->id}}">{{$carrera->carrera}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="escuela" class="form-label">Institución</label>
                        <select name="escuela" id="escuela" class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($escuelas as $escuela)
                                <option value="{{$escuela->id}}">{{$escuela->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" name="cedula" id="cedula" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="fecha_inicio">Fecha inicio estudios</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="fecha_final">Fecha final estudios</label>
                        <input type="date" name="fecha_final" id="fecha_final" class="form-control">
                    </div>
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Acciones adicionales')
        <div class="row">
            <ul>
                <li><a href="/rechumanos/personal/alta_carrera/{{ $id }}">Dar de alta una nueva carrera</a></li>
                <li><a href="/rechumanos/personal/alta_escuela/{{ $id }}">Dar de alta una nueva institución educativa</a></li>
            </ul>
        </div>
    </x-additional>
@stop

