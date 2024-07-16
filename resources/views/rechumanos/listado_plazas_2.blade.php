@extends('adminlte::page')

@section('title', 'Plazas')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <p>Determine la categoría de la plaza por ser buscada</p>
                <form action="{{route('rechumanos.busqueda_plaza_categoria')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="categoria" class="form-label">Categoría de la plaza</label>
                        <select name="categoria" id="categoriua" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}">({{$categoria->categoria}}) {{$categoria->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-3 col-12">
                        <input type="hidden" name="estatus" value="{{$estatus}}">
                        <button type="submit" class="btn btn-primary mb-4">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
