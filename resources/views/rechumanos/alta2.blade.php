@extends('adminlte::page')

@section('title', 'Alta Personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h3>Personal: {{$nombre}} {{$apellidos}}</h3>
        <i>Los datos marcados con (*) son requeridos</i>
        <br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('rechumanos.alta2')}}" method="post" role="form">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <label for="domicilio">(*) Domicilio (Calle y No)</label>
                    <input type="text" name="domicilio" id="domicilio" required
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="colonia">(*) Colonia</label>
                    <input type="text" name="colonia" id="colonia" required
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="cp">(*) C.P.</label>
                    <input type="text" name="cp" id="cp" required maxlength="5"
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="telefono">(*) Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" required
                           onchange="this.value=this.value.toUpperCase();"
                           class="form-control">
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="siglas">Siglas</label>
                    <input type="text" name="siglas" id="siglas"
                           onchange="this.value=this.value.toUpperCase();" maxlength="8"
                           class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <label for="gob">Alta Gobierno</label>
                    <input type="text" name="gob" id="gob" maxlength="6"
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="sep">Alta SEP</label>
                    <input type="text" name="sep" id="sep" maxlength="6"
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="rama">Alta Rama</label>
                    <input type="text" name="rama" id="rama" maxlength="6"
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <label for="area">(*) Área de adscripción</label>
                    <select name="area" id="area" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($deptos as $dpto)
                            <option value="{{$dpto->clave_area}}">{{$dpto->descripcion_area}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="id" value="{{$id}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
