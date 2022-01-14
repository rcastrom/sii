@extends('adminlte::page')

@section('title', 'Certificado')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">{{ $alumno->apellido_paterno.' '.$alumno->apellido_materno.' '.$alumno->nombre_alumno }}</h4>
        <br>
        <p class="card-text">Número de control: {{ $alumno->no_de_control }}</p>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('escolares.certificado')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="femision">Fecha de emisión</label>
                <input type="date" required class="form-control" name="femision" id="femision">
            </div>
            <div class="form-group">
                <label for="periodo">Período</label>
                <select name="periodo" id="periodo" required class="form-control">
                    @foreach($periodos as $per)
                        <option value="{{$per->periodo}}" {{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="iniciales">Iniciales del Jefe del Depto de S.E</label>
                <input type="text" name="iniciales" id="iniciales" required
                       onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="director">Nombre del Director</label>
                <input type="text" name="director" id="director" required
                       onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="registro">Número de registro</label>
                <input type="text" name="registro" id="registro" required
                       onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="libro">Libro</label>
                <input type="text" name="libro" id="libro" required
                       onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="foja">Foja</label>
                <input type="text" name="foja" id="foja" required
                       onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="fregistro">Fecha de registro</label>
                <input type="date" required class="form-control" name="fregistro" id="fregistro">
            </div>
            <div class="form-group">
                <label for="emite_equivalencia">Autoridad educativa que emitió la equivalencia</label>
                <input type="text" name="emite_equivalencia" id="emite_equivalencia" onchange="this.value=this.value.toUpperCase();" class="form-control" value="DGEST">
            </div>
            <div class="form-group">
                <label for="equivalencia">Folio de Equivalencia</label>
                <input type="text" name="equivalencia" id="equivalencia" onchange="this.value=this.value.toUpperCase();" class="form-control">
            </div>
            <div class="form-group">
                <label for="fequivalencia">Fecha de Equivalencia</label>
                <input type="date" class="form-control" name="fequivalencia" id="fequivalencia">
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de certificado</label>
                <select name="tipo" id="tipo" required class="form-control">
                    <option value="N" selected>Normal (concluido o incompleto)</option>
                    <option value="R">Reposición</option>
                    <option value="D">Duplicado</option>
                </select>
            </div>
            <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
