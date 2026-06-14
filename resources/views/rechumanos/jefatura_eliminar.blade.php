@extends('adminlte::page')

@section('title', 'Cargos directivos')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <form action="{{route('listado.destroy',$id)}}" method="post" class="row">
                    @csrf
                    @method('DELETE')
                    <div class="col-12 mb-3">
                        <label for="area" class="form-label">Puesto por ser eliminado</label>
                        <input type="text" name="area"
                               id="area" readonly value="{{$puesto->descripcion_area}}" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        ¿Confirma la eliminación del cargo?
                    </div>
                    <div class="form-floating">
                        <button type="submit" class="btn btn-primary mt-3">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
