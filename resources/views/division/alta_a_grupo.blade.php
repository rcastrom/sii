@extends('adminlte::page')

@section('title', 'Grupos')

@section('content_header')
    <h1>División de Estudios Profesionales</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <h4 class="card-title">Materia: {{$nmateria->nombre_completo_materia}} Grupo: {{$grupo}}</h4><br>
        <h5>Clave: {{$materia}}</h5><br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('dep_altaa')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="control">Indique por favor el número de control del estudiante</label>
                <input type="text" name="control" id="control" maxlength="10" required class="form-control">
            </div>
            <div class="form-group">
                <label for="global">Indique si la materia es global</label>
                <select name="global" id="global" class="form-control">
                    <option value="N" selected>No</option>
                    <option value="S">Sí</option>
                </select>
            </div>
            <input type="hidden" name="materia" id="materia" value="{{$materia}}">
            <input type="hidden" name="grupo" id="grupo" value="{{$grupo}}">
            <input type="hidden" name="periodo" id="periodo" value="{{$periodo}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
