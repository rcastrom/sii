@extends('adminlte::page')

@section('title', 'Alta Dirección')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Asignación de personal a puesto de confianza</h4>
                <p>Emplee el siguiente formulario para dar de alta a quien realizará
                alguna función administrativa en la Institución.
                </p>
                <p>El campo <i>descripción del área</i>, es para que indique
                    el nombre completo del cargo; como por ejemplo: "Jefa del Departamento
                    de Servicios Escolares", "Director", etcétera; ya que el sistema tomará
                    esta información para ciertos documentos que sean generados a partir del
                    mismo.
                </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('listado.store')}}" method="post" class="row">
                    @csrf
                    <div class="col-12 mb-3">
                        <label for="area" class="form-label">Puesto por ser dado de alta</label>
                        <select name="area" id="area" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($cargos as $cargo)
                                <option value="{{$cargo->clave_area}}">{{$cargo->descripcion_area}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="puesto" class="form-label">Descripción del área</label>
                        <input type="text" name="puesto"
                               id="puesto" required onchange="this.value=this.value.toUpperCase();"
                               class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="persona" class="form-label">Persona que tomará el puesto</label>
                        <select name="persona" id="persona" required class="form-control">
                            <option value="" selected>--Seleccione--</option>
                            @foreach($personal as $persona)
                                <option value="{{$persona->id}}">{{$persona->apellidos_empleado.' '.$persona->nombre_empleado}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" name="correo"
                               id="correo" onchange="this.value=this.value.toLowerCase();"
                               class="form-control">
                    </div>
                    <div class="form-floating">
                        <button type="submit" class="btn btn-primary mt-3">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
