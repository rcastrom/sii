@extends('adminlte::page')

@section('title', 'Alta período')

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

        <form action="{{route('periodo_escolar.store')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="anio" class="col-sm-6 col-form-label">Año (*)</label>
                <div class="col-sm-6">
                    <input type="number" name="anio" id="anio" class="form-control" value="{{$yr}}" required maxlength="4" onchange="this.value=this.value.toUpperCase();">
                </div>
            </div>
            <div class="form-group">
                <label for="tper" class="col-sm-6 col-form-label">Tipo de período(*)</label>
                <div class="col-sm-6">
                    <select name="tper" id="tper" required class="form-control">
                        <option value="" selected>--Seleccione--</option>
                        <option value="1">Enero - Junio</option>
                        <option value="2">Verano</option>
                        <option value="3">Agosto - Diciembre</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio" class="col-sm-6 col-form-label">Inicio del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio" id="finicio" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino" class="col-sm-6 col-form-label">Fin del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino" id="ftermino" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_ss" class="col-sm-6 col-form-label">Inicio vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_ss" id="finicio_ss" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_ss" class="col-sm-6 col-form-label">Fin vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_ss" id="ftermino_ss" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_vac" class="col-sm-6 col-form-label">Inicio de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_vac" id="finicio_vac" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_vac" class="col-sm-6 col-form-label">Fin de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_vac" id="ftermino_vac" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_cap" class="col-sm-6 col-form-label">Inicio captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_cap" id="finicio_cap" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_cap" class="col-sm-6 col-form-label">Fin captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_cap" id="ftermino_cap" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_est" class="col-sm-6 col-form-label">Inicio selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_est" id="finicio_est" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_est" class="col-sm-6 col-form-label">Fin selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_est" id="ftermino_est" class="form-control" required>
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
