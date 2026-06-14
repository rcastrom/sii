@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Usuarios del sistema</div>
                        <div class="card-body">
                            <form action="{{route('x_rol')}}" method="post" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="role">Indique el tipo de usuario (rol) por dar de alta</label>
                                    <select name="rol_id" id="rol_id" class="form-control" required>
                                        <option value="" selected>--Seleccione--</option>
                                        @foreach($roles as $rol)
                                            <option value="{{$rol->id}}">{{$rol->guard_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop

