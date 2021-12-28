@extends('adminlte::page')

@section('title', 'NSS')

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
        <form action="{{route('escolares.nss')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="nss">Indique el número de seguridad social</label>
                <input type="text" name="nss" id="nss" required class="form-control">
            </div>
            <div class="form-group">
                <input type="hidden" name="control" value="{{$alumno->no_de_control}}">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@stop
