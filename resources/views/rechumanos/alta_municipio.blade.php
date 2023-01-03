@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Alta de municipio</h4>
                <p>Emplee el siguiente formulario para dar de alta un municipio no enlistado en la
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
                <form action="{{route('rechumanos.alta_municipio')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="estado" class="form-label">Estado de la República Mexicana</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="" selected>--Seleccione--</option>
                            @foreach($estados as $estado)
                                <option value="{{$estado->entidad_federativa}}">{{$estado->nombre_entidad}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="municipio" class="form-label">Municipio a dar de alta</label>
                        <input type="text" name="municipio" id="municipio"
                               required class="form-control" onchange="this.value=this.value.toUpperCase();">
                    </div>
                    <input type="hidden" name="estudios" id="estudios" value="{{$estudio}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop



