@extends('adminlte::page')

@section('title', 'Especialidades')

@section('content_header')
    <h1>Servicios Escolares</h1>
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
        <form method="post" action="{{route('escolares.especialidad_alta')}}" role="form">
            @csrf
            <fieldset class="border p-2">
                <legend class="w-auto">Todos los datos son obligatorios</legend>
                <div class="form-group row">
                    <label for="espe" class="col-sm-6 col-form-label">Codificación de la especialidad</label>
                    <div class="col-sm-6">
                        <input type="text" id="espe" name="espe" required class="form-control" maxlength="5" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nespecialidad" class="col-sm-6 col-form-label">Nombre de la especialidad</label>
                    <div class="col-sm-6">
                        <input type="text" id="nespecialidad" name="nespecialidad" required class="form-control" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="carrera" class="col-sm-6 col-form-label">Asociar a la carrera</label>
                    <div class="col-sm-6">
                        <select name="carrera" id="carrera" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($carreras as $carr)
                                <option value="{{$carr->carrera."_".$carr->reticula}}">(Ret {{$carr->reticula}}) {{$carr->nombre_reducido}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cred_especialidad" class="col-sm-6 col-form-label">Créditos especialidad</label>
                    <div class="col-sm-6">
                        <input type="number" id="cred_especialidad" name="cred_especialidad" required class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cred_optativos" class="col-sm-6 col-form-label">Créditos optativos</label>
                    <div class="col-sm-6">
                        <input type="number" id="cred_optativos" name="cred_optativos" required class="form-control">
                    </div>
                </div>
                <x-aviso>
                    La codificación para la especialidad es única, por lo que de existir otra igual no se
                    podrá realizar el registro. El código máximo es de 5 caracteres
                </x-aviso>
                <button type="submit" class="btn btn-primary">Continuar</button>
            </fieldset>
        </form>
    </x-information>
@stop
