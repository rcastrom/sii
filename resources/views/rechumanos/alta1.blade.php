@extends('adminlte::page')

@section('title', 'Alta Personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
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
        <form action="{{route('rechumanos.alta1')}}" method="post" role="form">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <label for="apellido_paterno">Primer apellido</label>
                    <input type="text" name="apellido_paterno" id="apellido_paterno"
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="apellido_materno">Segundo apellido</label>
                    <input type="text" name="apellido_materno" id="apellido_materno" required
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
                <div class="col-sm-12 col-md-4">
                    <label for="nombre_empleado">Nombre(s)</label>
                    <input type="text" name="nombre_empleado" id="nombre_empleado" required
                           onchange="this.value=this.value.toUpperCase();" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="curp_empleado">CURP</label>
                    <input type="text" name="curp_empleado" id="curp_empleado" required
                           onchange="this.value=this.value.toUpperCase();" maxlength="18"
                           class="form-control">
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="rfc">RFC</label>
                    <input type="text" name="rfc" id="rfc" required
                           onchange="this.value=this.value.toUpperCase();" maxlength="13"
                           class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="sexo_empleado">Sexo</label>
                    <select name="sexo_empleado" id="sexo_empleado" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="estado_civil">Estado civil</label>
                    <select name="estado_civil" id="estado_civil" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="S">Soltero(a)</option>
                        <option value="C">Casado(a)</option>
                        <option value="D">Divorciado(a)</option>
                        <option value="V">Viudo(a)</option>
                        <option value="U">Unión Libre</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="correo_electronico">Correo electrónico</label>
                    <input type="email" name="correo_electronico" id="correo_electronico"
                           onchange="this.value=this.value.toLowerCase();" class="form-control" required>
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="no_tarjeta">Número de empleado</label>
                    <input type="number" name="no_tarjeta" id="no_tarjeta" required class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label for="nombramiento">Tipo de contratación</label>
                    <select name="nombramiento" id="nombramiento"  required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($contrataciones as $contratacion)
                            <option value="{{$contratacion->letra}}">{{$contratacion->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="status_empleado">Ingresa como</label>
                    <select name="status_empleado" id="status_empleado"  required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="6">Alta interina limitada (Mov 20)</option>
                        <option value="2">Base (Mov 95)</option>
                        <option value="7">Honorarios</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
