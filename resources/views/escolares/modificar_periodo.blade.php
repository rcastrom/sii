@extends('adminlte::page')

@section('title', 'Actualización de Período')

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
        <form action="{{route('periodo_escolar.update',["periodo_escolar"=>$periodo->id])}}" method="post" role="form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="finicio" class="col-sm-6 col-form-label">Inicio del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio" id="finicio" value="{{$periodo->fecha_inicio}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino" class="col-sm-6 col-form-label">Fin del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino" id="ftermino" value="{{$periodo->fecha_termino}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_ss" class="col-sm-6 col-form-label">Inicio vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_ss" id="finicio_ss" value="{{$periodo->inicio_vacacional_ss}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_ss" class="col-sm-6 col-form-label">Fin vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_ss" id="ftermino_ss" value="{{$periodo->fin_vacacional_ss}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_vac" class="col-sm-6 col-form-label">Inicio de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_vac" id="finicio_vac" value="{{$periodo->inicio_vacacional}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_vac" class="col-sm-6 col-form-label">Fin de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_vac" id="ftermino_vac" value="{{$periodo->termino_vacacional}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_cap" class="col-sm-6 col-form-label">Inicio captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_cap" id="finicio_cap" value="{{$periodo->inicio_cal_docentes}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_cap" class="col-sm-6 col-form-label">Fin captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_cap" id="ftermino_cap" value="{{$periodo->fin_cal_docentes}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_est" class="col-sm-6 col-form-label">Inicio selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_est" id="finicio_est" value="{{$periodo->inicio_sele_alumnos}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_est" class="col-sm-6 col-form-label">Fin selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_est" id="ftermino_est" value="{{$periodo->fin_sele_alumnos}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="horarios" class="col-sm-6 col-form-label">¿Se permite la captura de horarios?</label>
                <div class="col-sm-6">
                    <select name="horarios" id="horarios" class="form-control" required>
                        <option value="1" {{$periodo->cierre_horarios==1?' selected':''}}>Sí</option>
                        <option value="0" {{$periodo->cierre_horarios==0?' selected':''}}>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="seleccion" class="col-sm-6 col-form-label">¿Se permite la selección de materias por parte de las coordinaciones?</label>
                <div class="col-sm-6">
                    <select name="seleccion" id="seleccion" class="form-control">
                        <option value="1" {{$periodo->cierre_seleccion==1?' selected':''}}>Sí</option>
                        <option value="0" {{$periodo->cierre_seleccion==0?' selected':''}}>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cambio_carrera" class="col-sm-6 col-form-label">¿Se permite el cambio de carrera de primer semestre?</label>
                <div class="col-sm-6">
                    <select name="cambio_carrera" id="cambio_carrera" class="form-control">
                        <option value="1" {{$periodo->cambio_carrera==1?' selected':''}}>Sí</option>
                        <option value="0" {{$periodo->cambio_carrera==0?' selected':''}}>No</option>
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
