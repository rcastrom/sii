@extends('adminlte::page')

@section('title', 'Alta alumno')

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
        <div class="alert alert-dismissible">Los datos marcados con (*) son obligatorios</div>
        <div class="alert alert-info">{{$mensaje}}</div>
        <form action="{{route('escolares.nuevo_alumno')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="control" class="col-sm-4 col-form-label">Número de control (*)</label>
                <div class="col-sm-8">
                    <input type="text" name="control" id="control" class="form-control" required maxlength="10" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="appat" class="col-sm-4 col-form-label">Apellido Paterno</label>
                <div class="col-sm-8">
                    <input type="text" name="appat" id="appat" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="apmat" class="col-sm-4 col-form-label">Apellido Materno (*)</label>
                <div class="col-sm-8">
                    <input type="text" name="apmat" id="apmat" class="form-control" required onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="nombre" class="col-sm-4 col-form-label">Nombre (*)</label>
                <div class="col-sm-8">
                    <input type="text" name="nombre" id="nombre" class="form-control" required onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="carrera" class="col-sm-4 col-form-label">Carrera (*)</label>
                <div class="col-sm-8">
                    <select name="carrera" id="carrera" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($carreras as $carrera)
                            <option value="{{trim($carrera->carrera).'_'.$carrera->reticula.'_'.$carrera->nivel_escolar}}">(Ret {{$carrera->reticula}}) {{$carrera->nombre_reducido}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="plan" class="col-sm-4 col-form-label">Plan de estudios (*)</label>
                <div class="col-sm-8">
                    <select name="plan" id="plan" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($planes as $plan)
                            <option value="{{$plan->plan_de_estudio}}">(Plan {{$plan->plan_de_estudio}}) {{$plan->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ingreso" class="col-sm-4 col-form-label">Período de ingreso (*)</label>
                <div class="col-sm-8">
                    <select name="ingreso" id="ingreso" required class="form-control">
                        @foreach($periodos as $per)
                            <option value="{{$per->periodo}}" {{$per->periodo==$periodo?' selected':''}}>{{$per->identificacion_larga}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="semestre" class="col-sm-4 col-form-label">Semestre (*)</label>
                <div class="col-sm-8">
                    <input type="number" name="semestre" id="semestre" class="form-control" required onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="nss" class="col-sm-4 col-form-label">NSS</label>
                <div class="col-sm-8">
                    <input type="text" name="nss" id="nss" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="curp" class="col-sm-4 col-form-label">CURP (*)</label>
                <div class="col-sm-8">
                    <input type="text" name="curp" id="curp" class="form-control" required maxlength="18" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="lnac" class="col-sm-4 col-form-label">Lugar de nacimiento (*)</label>
                <div class="col-sm-8">
                    <select name="lnac" id="lnac" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($estados as $estado)
                            <option value="{{$estado->clave_entidad}}">{{$estado->nombre_entidad}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ciudad" class="col-sm-4 col-form-label">Ciudad de procedencia</label>
                <div class="col-sm-8">
                    <input type="text" name="ciudad" id="ciudad" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="fnac" class="col-sm-4 col-form-label">Fecha nacimiento (*)</label>
                <div class="col-sm-8">
                    <input type="date" name="fnac" id="fnac" required class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="sexo" class="col-sm-4 col-form-label">Sexo (*)</label>
                <div class="col-sm-8">
                    <select name="sexo" id="sexo" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ecivil" class="col-sm-4 col-form-label">Estado civil (*)</label>
                <div class="col-sm-8">
                    <select name="ecivil" id="ecivil" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="S">Soltero</option>
                        <option value="C">Casado</option>
                        <option value="D">Divorciado</option>
                        <option value="V">Viudo</option>
                        <option value="U">Unión libre</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="calle" class="col-sm-4 col-form-label">Calle y número</label>
                <div class="col-sm-8">
                    <input type="text" name="calle" id="calle" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="colonia" class="col-sm-4 col-form-label">Colonia</label>
                <div class="col-sm-8">
                    <input type="text" name="colonia" id="colonia" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="cp" class="col-sm-4 col-form-label">CP</label>
                <div class="col-sm-8">
                    <input type="text" name="cp" id="cp" class="form-control" maxlength="5" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="telcel" class="col-sm-4 col-form-label">Teléfono</label>
                <div class="col-sm-8">
                    <input type="text" name="telcel" id="telcel" class="form-control" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="correo" class="col-sm-4 col-form-label">Correo electrónico</label>
                <div class="col-sm-8">
                    <input type="email" name="correo" id="correo" class="form-control" onchange="this.value=this.value.toLowerCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="proc" class="col-sm-4 col-form-label">Escuela de procedencia</label>
                <div class="col-sm-8">
                    <input type="text" name="proc" id="proc" class="form-control" onchange="this.value=this.value.toLowerCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="rev" class="col-sm-4 col-form-label">Semestres revalidados</label>
                <div class="col-sm-8">
                    <input type="number" name="rev" id="rev" class="form-control" onchange="this.value=this.value.toLowerCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="tipo" class="col-sm-4 col-form-label">Tipo de ingreso (*)</label>
                <div class="col-sm-8">
                    <select name="tipo" id="tipo" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        @foreach($tipos_ingreso as $tingreso)
                            <option value="{{$tingreso->id}}">{{$tingreso->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </div>
        </form>
    </x-information>
@stop
