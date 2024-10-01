@extends('adminlte::page')

@section('title', 'Materias')

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
        <form method="post" action="{{route('escolares.materia_actualizar')}}" role="form">
            @csrf
            <div class="form-group">
                <label for="cve">Clave interna para la materia</label>
                <input type="text" class="form-control" name="cve" id="cve"
                       readonly value="{{$materia}}">
            </div>
            <div class="form-group">
                <label for="nivel">Nivel escolar</label>
                <select name="nivel" id="nivel" required class="form-control">
                    <option value="L" {{$datos->nivel=="L"?"selected":""}}>Licenciatura</option>
                    <option value="P" {{$datos->nivel=="P"?"selected":""}}>Posgrado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="area">Área académica</label>
                <select name='area' id="area" class="form-control" required>
                    @foreach($acad as $academico)
                        <option value="{{$academico->clave_area}}" {{$datos->area==$academico->clave_area?"selected":""}}>{{$academico->descripcion_area}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cve_of">Clave oficial para la materia</label>
                <input type="text" class="form-control" value="{{$datos->clave_oficial_materia}}"
                       name="cve_of" id="cve_of" required maxlength="7"
                       onchange="this.value=this.value.toUpperCase();">
            </div>
            <div class="form-group">
                <label for="nombre_completo">Nombre completo</label>
                <input type="text" class="form-control" value="{{$datos->nombre_completo_materia}}"
                       name="nombre_completo" id="nombre_completo"
                       required onchange="this.value=this.value.toUpperCase();">
            </div>
            <div class="form-group">
                <label for="nombre_abrev">Nombre abreviado</label>
                <input type="text" class="form-control" value="{{$datos->nombre_abreviado_materia}}"
                       name="nombre_abrev" id="nombre_abrev"
                       required onchange="this.value=this.value.toUpperCase();">
            </div>
            <div class="form-group">
                <label for="horas_teoricas">Horas teóricas</label>
                <input type="number" class="form-control" value="{{$datos->horas_teoricas}}"
                       name="horas_teoricas" id="horas_teoricas" required>
            </div>
            <div class="form-group">
                <label for="horas_practicas">Horas prácticas</label>
                <input type="number" class="form-control" value="{{$datos->horas_practicas}}"
                       name="horas_practicas" id="horas_practicas" required>
            </div>
            <div class="form-group">
                <label for="creditos">Créditos totales</label>
                <input type="number" class="form-control" value="{{$datos->creditos_materia}}"
                       name="creditos" id="creditos" required>
            </div>
            <div class="form-group">
                <label for="certificado">Órden de la materia en el certificado</label>
                <input type="number" class="form-control" value="{{$datos->orden_certificado}}"
                       name="certificado" id="certificado" required>
            </div>
            <div class="form-group">
                <label for="especialidad">Indique si pertenece a alguna especialidad</label>
                <select name='especialidad' id="especialidad" class="form-control" required>
                    <option value="0" {{$datos->especialidad==0?"selected":""}}>No pertenece a ninguna especialidad</option>
                    @foreach($espe as $especialidad)
                        <option value="{{$especialidad->especialidad}}" {{$especialidad->especialidad==$datos->$especialidad?"selected":""}}>{{$especialidad->nombre_especialidad}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="semestre">Semestre de asignación</label>
                <select name='semestre' id="semestre" class="form-control" required>
                    @for($i=1;$i<=9;$i++)
                        <option value="{{$i}}" {{$i==$datos->semestre_reticula?"selected":""}}>{{$i}}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="renglon">Renglón de asignación</label>
                <select name='renglon' id="renglon" class="form-control" required>
                    @for($i=1;$i<=10;$i++)
                        <option value="{{$i}}" {{$i==$datos->renglon?"selected":""}}>{{$i}}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="caracterizacion">Caracterización</label>
                <textarea name="caracterizacion" id="caracterizacion"
                          onchange="this.value=this.value.toUpperCase();"
                          cols="30" rows="10" class="form-control">{{$datos->caracterizacion}}
                </textarea>
            </div>
            <div class="form-group">
                <label for="generales">Generales</label>
                <textarea name="generales" id="generales"
                          onchange="this.value=this.value.toUpperCase();"
                          cols="30" rows="10" class="form-control">{{$datos->generales}}
                </textarea>
            </div>
            <input type="hidden" name="materia" value="{{$materia}}">
            <input type="hidden" name="carrera" value="{{$carrera}}">
            <input type="hidden" name="reticula" value="{{$reticula}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop

