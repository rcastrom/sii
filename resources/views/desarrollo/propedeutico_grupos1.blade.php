@extends('adminlte::page')

@section('title', 'Gpo Propedéutico')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="card-header">
            <div class="card-title">El sistema tomará como período de referencia a {{$nombre_periodo}} </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('desarrollo.alta_grupo')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="materia">Materia</label>
                    <input type="text" name="materia" id="materia" required
                           class="form-control" onChange="this.value=this.value.toUpperCase();">
                </div>
                <div class="form-group">
                    <label for="nombre_corto">Nombre corto</label>
                    <input type="text" name="nombre_corto" id="nombre_corto" maxlength="20"
                           required onChange="this.value=this.value.toUpperCase();"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="grupo">Grupo</label>
                    <input type="text" name="grupo" id="grupo" maxlength="2"
                           required onChange="this.value=this.value.toUpperCase();"
                           class="form-control">
                </div>
                <input type="hidden" name="periodo" value="{{$periodo}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </form>
        </div>
    </x-information>
    <x-additional>
        @slot('header','Grupos propedéuticos existentes en el período')
        @if($bandera)
            <div class="row">
                <div class="col-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4">
                                Materia / Grupo
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                Docente / Aula
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card-header">
                        <div class="row">
                            &nbsp;
                        </div>
                        <div class="col">
                            Acciones
                        </div>
                    </div>
                </div>
            </div>
            @foreach($grupos as $grupo)
                <div class="row">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-8">
                                {{$grupo->materia."/ Gpo".$grupo->grupo}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="col-12">
                                    Docente {{is_null($grupo->apellidos_empleado)?"sin asignar":$grupo->apellidos_empleado.' '.$grupo->nombre_empleado}}
                                    Aula {{is_null($grupo->aula)?"sin asignar":$grupo->aula}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-2">
                                <i class="fa fa-pen">
                                    <a href="{{route('desarrollo.informe_grupo',
                                    ["id"=>$grupo->id,"periodo"=>$periodo])}}">
                                    Editar
                                    </a>
                                </i>
                            </div>
                            <div class="col-2">
                                <i class="fa fa-cloud">
                                    <a href="{{route('desarrollo.aula_grupo',
                                    ["id"=>$grupo->id,"periodo"=>$periodo])}}">
                                        Asignación aula
                                    </a>
                                </i>
                            </div>
                            <div class="col-2">
                                <i class="fa fa-user-times">
                                    <a href="{{route('desarrollo.docente_grupo',
                                    ["id"=>$grupo->id,"periodo"=>$periodo])}}">
                                        Asignación docente
                                    </a>
                                </i>
                            </div>
                            <div class="col-2">
                                <i class="fa fa-trash">
                                    <a href="{{route('desarrollo.grupo_eliminar',
                                    ["id"=>$grupo->id,"periodo"=>$periodo])}}">
                                        Eliminar
                                    </a>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-4">
                    &nbsp;
                </div>
                <div class="col-8 mt-3">
                    En la acción de editar, es donde podrá realizar la asignación del horario para la materia
                </div>
            </div>
        @else
            No hay grupos registrados
        @endif
    </x-additional>
@stop
