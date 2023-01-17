@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Actualización de carrera estudiada</h4>
                <p>Emplee el siguiente formulario para dar de alta una carrera no enlistada en la
                sección anterior, para estudios del personal</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('rechumanos.alta_carrera')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="carrera" class="form-label">Nombre de la carrera</label>
                        <input type="text" name="carrera" id="carrera"
                               class="form-control" required onchange="this.value=this.value.toUpperCase();">
                    </div>
                    <div class="col-12">
                        <label for="nombre_corto" class="form-label">Nombre abreviado de la carrera</label>
                        <input type="text" name="nombre_corto" id="nombre_corto"
                               class="form-control" required onchange="this.value=this.value.toUpperCase();">
                    </div>
                    <div class="col-12">
                        <label for="siglas" class="form-label">Siglas de la carrera</label>
                        <input type="text" name="siglas" id="siglas"
                               class="form-control" required onchange="this.value=this.value.toUpperCase();">
                    </div>
                    <div class="col-12">
                        <label for="nivel" class="form-label">Nivel educativo</label>
                        <select name="nivel" id="nivel" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($niveles as $nivel)
                                <option value="{{$nivel->caracter}}">{{$nivel->caracter."- ".$nivel->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="estudios" id="estudios" value="{{$estudio}}">
                    <input type="hidden" name="bandera" id="bandera" value="{{$bandera}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mb-4">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
