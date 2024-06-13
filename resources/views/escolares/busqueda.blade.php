@extends('adminlte::page')

@section('title', 'Inicio')

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
        <form method="post" action="{{route('escolares.buscar')}}" role="form">
            @csrf
            <fieldset>
                <div class="row gap-4">
                    <div class="col-12">
                        <label for="tbusqueda" class="col-form-label"> Buscar por: </label>
                        <select name="tbusqueda" id="tbusqueda" class="form-control">
                            <option value="1" selected>Número de control</option>
                            <option value="2">Apellido</option>
                        </select>
                    </div>
                </div>
                <div class="row gap-4 mt-4">
                    <div class="col-12">
                        <input type="text" name="control" id="control" class="form-control"
                               placeholder="Ingrese la información" aria-label="Ingrese la información"
                               required maxlength="10" onchange="this.value=this.value.toUpperCase();">
                    </div>
                </div>
            </fieldset>
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </div>
        </form>
    </x-information>
@stop
