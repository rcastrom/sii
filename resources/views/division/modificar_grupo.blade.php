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
                    <input type="time" name="elunes" id="elunes" class="form-control" value="{{isset($lunes)?date('H:i',strtotime($lunes->hora_inicial)):null}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="slunes" id="slunes" class="form-control" value="{{isset($lunes)?date('H:i',strtotime($lunes->hora_final)):null}}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_l" id="aula_l" class="form-control">
                        @if(!empty($lunes))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($lunes->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
                    <input type="time" name="emartes" id="emartes" class="form-control" value="{{ isset($martes)?date('H:i',strtotime($martes->hora_inicial)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smartes" id="smartes" class="form-control" value="{{ isset($martes)?date('H:i',strtotime($martes->hora_final)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_m" id="aula_m" class="form-control">
                        @if(!empty($martes))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($martes->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
                    <input type="time" name="emiercoles" id="emiercoles" class="form-control" value="{{ isset($miercoles)?date('H:i',strtotime($miercoles->hora_inicial)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="smiercoles" id="smiercoles" class="form-control" value="{{ isset($miercoles)?date('H:i',strtotime($miercoles->hora_final)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_mm" id="aula_mm" class="form-control">
                        @if(!empty($miercoles))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($miercoles->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
                    <input type="time" name="ejueves" id="ejueves" class="form-control" value="{{ isset($jueves)?date('H:i',strtotime($jueves->hora_inicial)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sjueves" id="sjueves" class="form-control" value="{{ isset($jueves)?date('H:i',strtotime($jueves->hora_final)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_j" id="aula_j" class="form-control">
                        @if(!empty($jueves))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($jueves->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
                    <input type="time" name="eviernes" id="eviernes" class="form-control" value="{{ isset($viernes)?date('H:i',strtotime($viernes->hora_inicial)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="sviernes" id="sviernes" class="form-control" value="{{ isset($viernes)?date('H:i',strtotime($viernes->hora_final)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_v" id="aula_v" class="form-control">
                        @if(!empty($viernes))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($viernes->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
                    <input type="time" name="esabado" id="esabado" class="form-control" value="{{ isset($sabado)?date('H:i',strtotime($sabado->hora_inicial)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <input type="time" name="ssabado" id="ssabado" class="form-control" value="{{ isset($sabado)?date('H:i',strtotime($sabado->hora_final)):null }}">
                </div>
                <div class="col-sm-3 col-md-3">
                    <select name="aula_s" id="aula_s" class="form-control">
                        @if(!empty($sabado))
                            @foreach($aulas as $aula)
                                <option value="{{trim($aula->aula)}}"{{trim($aula->aula)==trim($sabado->aula)?" selected":""}}>{{trim($aula->aula)}}</option>
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
