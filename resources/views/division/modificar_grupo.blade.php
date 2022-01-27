@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h6 class="card-title">Materia: {{$mater->nombre_abreviado_materia}} Grupo{{$grupo}}</h6><br>
        <h6 class="card-title">Horas por programar: {{$mater->creditos_materia}}</h6><br>
        Indique la información correspondiente al grupo por ser modificado
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('dep_grupo_modifica')}}" method="post" role="form">
            @csrf
            <div class="form-group row">
                <label for="capacidad" class="col-sm-3 col-md-8 col-form-label">Capacidad</label>
                <div class="col-sm-8 col-md-3">
                    <input type="number" required class="form-control"
                           name="capacidad" id="capacidad" value="{{old('capacidad',$grupo_existente[0]->capacidad_grupo)}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="elunes" class="col-sm-3 col-md-3 col-form-label">Lunes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="elunes" id="elunes" class="form-control" value="{{old('elunes',!empty($lunes[0]->hora_inicial)?$lunes[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="slunes" id="slunes" class="form-control" value="{{old('slunes',!empty($lunes[0]->hora_final)?$lunes[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_l" id="aula_l" class="form-control">
                        @if(!empty($lunes[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($lunes[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="emartes" class="col-sm-3 col-md-3 col-form-label">Martes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="emartes" id="emartes" class="form-control" value="{{old('emartes',!empty($martes[0]->hora_inicial)?$martes[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smartes" id="smartes" class="form-control" value="{{old('smartes',!empty($martes[0]->hora_final)?$martes[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_m" id="aula_m" class="form-control">
                        @if(!empty($martes[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($martes[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="emiercoles" class="col-sm-3 col-md-3 col-form-label">Miércoles</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="emiercoles" id="emiercoles" class="form-control" value="{{old('emiercoles',!empty($miercoles[0]->hora_inicial)?$miercoles[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smiercoles" id="smiercoles" class="form-control" value="{{old('smiercoles',!empty($miercoles[0]->hora_final)?$miercoles[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_mm" id="aula_mm" class="form-control">
                        @if(!empty($miercoles[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($miercoles[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="ejueves" class="col-sm-3 col-md-3 col-form-label">Jueves</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="ejueves" id="ejueves" class="form-control" value="{{old('ejueves',!empty($jueves[0]->hora_inicial)?$jueves[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sjueves" id="sjueves" class="form-control" value="{{old('sjueves',!empty($jueves[0]->hora_final)?$jueves[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_j" id="aula_j" class="form-control">
                        @if(!empty($jueves[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($jueves[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="eviernes" class="col-sm-3 col-md-3 col-form-label">Viernes</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="eviernes" id="eviernes" class="form-control" value="{{old('eviernes',!empty($viernes[0]->hora_inicial)?$viernes[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sviernes" id="sviernes" class="form-control" value="{{old('sviernes',!empty($viernes[0]->hora_final)?$viernes[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_v" id="aula_v" class="form-control">
                        @if(!empty($viernes[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($viernes[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="esabado" class="col-sm-3 col-md-3 col-form-label">Sábado</label>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="esabado" id="esabado" class="form-control" value="{{old('esabado',!empty($sabado[0]->hora_inicial)?$sabado[0]->hora_inicial:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="ssabado" id="ssabado" class="form-control" value="{{old('ssabado',!empty($sabado[0]->hora_final)?$sabado[0]->hora_final:null)}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_s" id="aula_s" class="form-control">
                        @if(!empty($sabado[0]->aula))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($sabado[0]->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
                            @endforeach
                        @else
                            <option value="" selected>--Seleccione</option>
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}">{{trim($aula->aula)}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
            <input type="hidden" name="materia" id="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" id="grupo" value="{{$grupo}}">
            <input type="hidden" name="creditos" id="creditos" value="{{$mater->creditos_materia}}">
            <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
        </form>
    </x-information>
@stop
