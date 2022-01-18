@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h5>Carrera {{$ncarrera->nombre_reducido}} Reticula {{$ncarrera->reticula}}</h5>
        <h6 class="card-title">Materia: {{$nmateria->nombre_abreviado_materia}}</h6><br>
        <h6 class="card-title">Horas por programar: {{$nmateria->creditos_materia}}</h6><br>
        Indique la información correspondiente al grupo por ser creado
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('dep_grupo_alta')}}" method="post" role="form">
            @csrf
            <div class="form-group row">
                <label for="grupo" class="col-sm-3 col-md-8 col-form-label">Grupo</label>
                <div class="col-sm-8 col-md-3">
                    <input type="text" required class="form-control" name="grupo" id="grupo" maxlength="3" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group row">
                <label for="capacidad" class="col-sm-3 col-md-8 col-form-label">Capacidad</label>
                <div class="col-sm-8 col-md-3">
                    <input type="number" required class="form-control" name="capacidad" id="capacidad">
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Lunes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="elunes" id="elunes" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="slunes" id="slunes" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_l" id="aula_l" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Martes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="emartes" id="emartes" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smartes" id="smartes" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_m" id="aula_m" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Miércoles</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="emiercoles" id="emiercoles" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smiercoles" id="smiercoles" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_mm" id="aula_mm" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Jueves</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="ejueves" id="ejueves" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sjueves" id="sjueves" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_j" id="aula_j" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Viernes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="eviernes" id="eviernes" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sviernes" id="sviernes" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_v" id="aula_v" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Sábado</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="esabado" id="esabado" class="form-control" placeholder="Hora entrada">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="ssabado" id="ssabado" class="form-control" placeholder="Hora salida">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_s" id="aula_s" class="form-control">
                        <option value="" selected>--Aula--</option>
                        @foreach($aulas as $aula)
                            <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
            <input type="hidden" name="carrera" value="{{$carrera}}">
            <input type="hidden" name="reticula" value="{{$ret}}">
            <input type="hidden" name="materia" value="{{$materia}}">
            <input type="hidden" name="creditos" value="{{$nmateria->creditos_materia}}">
            <input type="hidden" name="periodo" value="{{$periodo}}">
        </form>
    </x-information>
@stop
