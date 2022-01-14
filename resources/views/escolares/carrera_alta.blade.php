@extends('adminlte::page')

@section('title', 'Carreras')

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
        <form method="post" action="{{route('escolares.carrera_alta')}}"  role="form">
            @csrf
            <fieldset class="border p-2">
                <legend class="w-auto">Todos los datos son obligatorios</legend>
                <div class="form-group row">
                    <label for="carrera" class="col-sm-6 col-form-label">Codificación de la carrera</label>
                    <div class="col-sm-6">
                        <input type="text" id="carrera" name="carrera" required class="form-control" maxlength="3" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reticula" class="col-sm-6 col-form-label">Retícula</label>
                    <div class="col-sm-6">
                        <input type="number" id="reticula" name="reticula" required class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nivel" class="col-sm-6 col-form-label">Nivel escolar</label>
                    <div class="col-sm-6">
                        <select name="nivel" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            <option value="L">Licenciatura</option>
                            <option value="P">Posgrado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cve" class="col-sm-6 col-form-label">Clave oficial</label>
                    <div class="col-sm-6">
                        <input type="text" id="cve" name="cve" required class="form-control" maxlength="20" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ncarrera" class="col-sm-6 col-form-label">Nombre de la carrera</label>
                    <div class="col-sm-6">
                        <input type="text" id="ncarrera" name="ncarrera" required class="form-control" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nreducido" class="col-sm-6 col-form-label">Nombre abreviado de carrera</label>
                    <div class="col-sm-6">
                        <input type="text" id="nreducido" name="nreducido" required class="form-control" maxlength="20" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="siglas" class="col-sm-6 col-form-label">Siglas</label>
                    <div class="col-sm-6">
                        <input type="text" id="siglas" name="siglas" required class="form-control" maxlength="10" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cred_max" class="col-sm-6 col-form-label">Carga máxima</label>
                    <div class="col-sm-6">
                        <input type="number" id="cred_max" name="cred_max" required class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cred_min" class="col-sm-6 col-form-label">Carga mínima</label>
                    <div class="col-sm-6">
                        <input type="number" id="cred_min" name="cred_min" required class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cred_tot" class="col-sm-6 col-form-label">Créditos totales</label>
                    <div class="col-sm-6">
                        <input type="number" id="cred_tot" name="cred_tot" required class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="modalidad" class="col-sm-6 col-form-label">Modalidad</label>
                    <div class="col-sm-6">
                        <select name="modalidad" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            <option value="E">Escolarizado</option>
                            <option value="D">Distancia</option>
                        </select>
                    </div>
                </div>
                <x-aviso>
                    Históricamente para las carreras de Licenciatura, se ha asignado su codificación
                    en forma numérica; en donde hasta el momento, el último código es {{$cant}}.
                    Por favor, procure emplear un valor numérico para dicha codificación.
                </x-aviso>
                <button type="submit" class="btn btn-primary">Continuar</button>
            </fieldset>
        </form>
    </x-information>
@stop
