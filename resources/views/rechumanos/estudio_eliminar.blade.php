@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h5>Confirmar</h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Carrera</th>
                        <th>Realizado en </th>
                        <th>Cédula</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$nivel}}</td>
                            <td>{{$carrera->carrera}}</td>
                            <td>{{$escuela->nombre}}</td>
                            <td>{{$informacion->cedula}}</td>
                        </tr>
                        </tbody>
                </table>
                <form action="{{route('rechumanos.borrar_estudio')}}" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="confirmar" id="confirmar1" required value="1">
                            <label class="form-check-label" for="confirmar1">
                                Sí, borrar registro
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="confirmar" id="confirmar2" value="0">
                            <label class="form-check-label" for="confirmar2">
                                Cancelar
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop

