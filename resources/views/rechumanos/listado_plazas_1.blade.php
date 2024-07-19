@extends('adminlte::page')

@section('title', 'Plazas')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Plazas de la Institución</h4>
                <p>Determine la manera en cómo se visualizarán las plazas</p>
                <form action="{{route('rechumanos.busqueda_plazas')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="busqueda" class="form-label">Tipo de búsqueda</label>
                        <select name="busqueda" id="busqueda" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            <option value="1">En base a una categoría específica</option>
                            <option value="2">Todas las categorías</option>
                        </select>
                    </div>
                    <div class="mt-3 col-12">
                        <label for="estatus" class="form-label">Estatus de la plaza</label>
                        <select name="estatus" id="estatus" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            <option value="1">Actual</option>
                            <option value="2">Histórico</option>
                            <option value="3">Todas</option>
                        </select>
                    </div>
                    <div class="mt-3 col-12">
                        <button type="submit" class="btn btn-primary mb-4">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
