@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
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
        <form action="{{route('nodocente.store')}}" method="post" role="form">
            @csrf
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Asignación de actividad de horario administrativo</div>
                            <div class="card-body">
                                <h4 class="card-title">Catálogo de puestos</h4><br>
                                <div class="form-group">
                                    <label for="puesto">Seleccione el puesto a realizar del siguiente listado</label>
                                    <select name="puesto" id="puesto" required class="form-control">
                                        <option value="" selected>--Seleccione--</option>
                                        @foreach($puestos as $puesto)
                                            <option value="{{$puesto->clave_puesto}}">{{$puesto->descripcion_puesto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unidad">Indique el área de quien dependerá dicho puesto (unidad orgánica de adscripción)
                                        <select name="unidad" id="unidad" required class="form-control">
                                            <option value="" selected>--Seleccione--</option>
                                            @foreach($areas as $area)
                                                <option value="{{$area->clave_area}}">{{$area->descripcion_area}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="observacion">Observaciones para el horario</label>
                                    <p>En caso de requerir alguna observación especial para el horario, favor de indicarlo
                                    en el siguiente recuadro</p>
                                    <textarea name="observacion" id="observacion"
                                              cols="20" rows="5" onblur="this.value=this.value.toUpperCase();"
                                              class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Lunes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="elunes" id="elunes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="slunes" id="slunes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Martes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="emartes" id="emartes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="smartes" id="smartes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Miércoles
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="emiercoles" id="emiercoles">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="smiercoles" id="smiercoles">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Jueves
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="ejueves" id="ejueves">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="sjueves" id="sjueves">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Viernes
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="eviernes" id="eviernes">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="sviernes" id="sviernes">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        Sábado
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="esabado" id="esabado">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="ssabado" id="ssabado">
                                    </div>
                                </div>
                                <div class="form-group">

                                    <input type="hidden" name="periodo" value="{{$periodo}}">
                                    <input type="hidden" name="personal" value="{{$personal}}">
                                    <button type="submit" class="btn btn-primary">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-information>
@stop
