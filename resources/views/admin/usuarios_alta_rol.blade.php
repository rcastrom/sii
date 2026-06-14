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
                        <div class="card-header">Acceso al sistema</div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{route('crear_usuario')}}" method="post" role="form">
                                @csrf
                                <div class="form-group">
                                    <label for="nombre">Nombre del usuario (nombre-apellido)</label>
                                    <input type="text" name="nombre" id="required"
                                           onblur="this.value=this.value.toUpperCase()"
                                           required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="usuario">Usuario (en forma de correo)</label>
                                    <input type="email" name="usuario" id="usuario" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="contra">Contraseña</label>
                                    <input type="password" name="contra" id="contra" required
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="ccontra">Verifique contraseña</label>
                                    <input type="password" name="ccontra" id="ccontra"
                                           required class="form-control">
                                </div>
                                @php
                                    $bandera=!empty($permisos)?1:0;
                                @endphp
                                @if(!empty($permisos))
                                    <div class="card-header">Permisos por asignar</div>
                                    @foreach($permisos as $permiso)
                                        <div class="form-check">
                                            <input type="checkbox" name="permisos[]" id="{{$permiso->id}}"
                                                   class="form-check-input" checked value="{{$permiso->descripcion}}">
                                            <label for="permisos[]" class="form-check-label">
                                                {{$permiso->descripcion}}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                                <input type="hidden" name="rol" value="{{$rol->name}}">
                                <input type="hidden" name="bandera" value="{{$bandera}}">
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-information>
@stop

