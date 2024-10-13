@extends('adminlte::page')

@section('title', 'Contraseña')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
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
        <form action="{{route('academicos.contra')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="contra">Nueva contraseña</label>
                <input type="password" class="form-control" required name="contra" id="contra">
            </div>
            <div class="form-group">
                <label for="verifica">Confirmar contraseña</label>
                <input type="password" class="form-control" required name="verifica" id="verifica">
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
