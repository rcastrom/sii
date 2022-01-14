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
        <form action="{{route('escolares.periodo_upd')}}" method="post" role="form">
            @csrf
            <div class="form-group">
                <label for="finicio" class="col-sm-6 col-form-label">Inicio del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio" id="finicio" value="{{$periodos->fecha_inicio}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino" class="col-sm-6 col-form-label">Fin del semestre (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino" id="ftermino" value="{{$periodos->fecha_termino}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_ss" class="col-sm-6 col-form-label">Inicio vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_ss" id="finicio_ss" value="{{$periodos->inicio_vacacional_ss}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_ss" class="col-sm-6 col-form-label">Fin vacaciones semana santa</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_ss" id="ftermino_ss" value="{{$periodos->fin_vacacional_ss}}" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_vac" class="col-sm-6 col-form-label">Inicio de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_vac" id="finicio_vac" value="{{$periodos->inicio_vacacional}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_vac" class="col-sm-6 col-form-label">Fin de vacaciones (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_vac" id="ftermino_vac" value="{{$periodos->termino_vacacional}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_cap" class="col-sm-6 col-form-label">Inicio captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_cap" id="finicio_cap" value="{{$periodos->inicio_cal_docentes}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_cap" class="col-sm-6 col-form-label">Fin captura docente (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_cap" id="ftermino_cap" value="{{$periodos->fin_cal_docentes}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="finicio_est" class="col-sm-6 col-form-label">Inicio selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="finicio_est" id="finicio_est" value="{{$periodos->inicio_sele_alumnos}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="ftermino_est" class="col-sm-6 col-form-label">Fin selección materias (*)</label>
                <div class="col-sm-6">
                    <input type="date" name="ftermino_est" id="ftermino_est" value="{{$periodos->fin_sele_alumnos}}" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="horarios" class="col-sm-6 col-form-label">¿Se permite la captura de horarios?</label>
                <div class="col-sm-6">
                    <select name="horarios" id="horarios" class="form-control" required>
                        <option value="1" {{$periodos->cierre_horarios==1?' selected':''}}>Sí</option>
                        <option value="2" {{$periodos->cierre_horarios==2?' selected':''}}>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="seleccion" class="col-sm-6 col-form-label">¿Se permite la selección de materias por parte de las coordinaciones?</label>
                <div class="col-sm-6">
                    <select name="seleccion" id="seleccion" class="form-control">
                        <option value="1" {{$periodos->cierre_seleccion==1?' selected':''}}>Sí</option>
                        <option value="2" {{$periodos->cierre_seleccion==2?' selected':''}}>No</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ccarrera" class="col-sm-6 col-form-label">¿Se permite el cambio de carrera de primer semestre?</label>
                <div class="col-sm-6">
                    <select name="seleccion" id="seleccion" class="form-control">
                        <option value="1" {{$periodos->ccarrera==1?' selected':''}}>Sí</option>
                        <option value="0" {{$periodos->ccarrera==0?' selected':''}}>No</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="periodo" value="{{$periodo}}">
            <div class="form-group">
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </div>
        </form>
    </x-information>
@stop
