@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Actualización de datos de estudio</h4>
                <h5>{{$nombre}}</h5>
                <form action="{{route('rechumanos.actualizar_estudios')}}" method="post" class="row">
                    @method('PUT')
                    @csrf
                    <div class="col-12">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select name="carrera" id="carrera" class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($carreras as $carrera)
                                @php
                                    $txt=$carrera->id==$informacion->id_carrera?' selected':'';
                                @endphp
                                <option value="{{$carrera->id}}" {{$txt}}>{{$carrera->carrera}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="escuela" class="form-label">Institución</label>
                        <select name="escuela" id="escuela" class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($escuelas as $escuela)
                                @php
                                $txt=$escuela->id==$informacion->id_escuela?' selected':'';
                                @endphp
                                <option value="{{$escuela->id}}" {{$txt}}>{{$escuela->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" name="cedula" id="cedula" class="form-control" value="{{$informacion->cedula}}">
                    </div>
                    <div class="col-12">
                        <label for="fecha_inicio">Fecha inicio estudios</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{$informacion->fecha_inicio}}">
                    </div>
                    <div class="col-12">
                        <label for="fecha_final">Fecha final estudios</label>
                        <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="{{$informacion->fecha_final}}">
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
                <li><a href="/rechumanos/personal/alta_carrera/{{ $id }}/0">Dar de alta una nueva carrera</a></li>
                <li><a href="/rechumanos/personal/alta_escuela/{{ $id }}/0">Dar de alta una nueva institución educativa</a></li>
            </ul>
        </div>
    </x-additional>
@stop
