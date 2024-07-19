@extends('adminlte::page')

@section('title', 'Consulta de Personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">

        <form action="{{route('rechumanos.listado')}}" method="post" role="form" class="row">
            @csrf
            <div class="col-12 mb-3">
                <label for="estatus" class="form-label">Tipo de consulta en base al estatus del personal</label>
                <select name="estatus" id="estatus" required class="form-control">
                    @foreach($situaciones as $situacion)
                        <option value="{{$situacion->estatus}}">{{$situacion->descripcion}}</option>
                    @endforeach
                        <option value="T" selected>Todos</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
