@extends('adminlte::page')

@section('title', 'Aspirantes')

@section('content_header')
    <h1>Desarrollo Académico</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">IMPORTANTE</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                Al momento de seleccionar a un aspirante, debe indicar el grupo al cuál el
                                estudiante será dado de alta.
                                Al momento en el que Escolares le genere su número de control correspondiente,
                                el sistema le asignará la carga académica del grupo que usted haya seleccionado
                                y que estén registradas en primer semestre.
                            </div>
                            <div class="col-4">
                                <strong>SI NO EXISTE UN GRUPO</strong> es decir, si el área académica no ha creado
                                los grupos correspondientes al primer semestre, NO PODRÁ AVANZAR.
                            </div>
                            <div class="col-4">
                                Una vez que cuente con carga académica, solamente los usuarios del grupo
                                de División de Estudios Profesionales serán los únicos que pueden
                                realizarle el cambio correspondiente
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        Indique el período para el cuál, desea visualizar los datos de los aspirantes a ingresar
        <form action="{{route('desarrollo.seleccionar_listado')}}" method="post" class="form-inline" role="form">
            @csrf
            <label class="sr-only" for="periodo">Periodo</label>
            <select name="periodo" id="periodo" required class="form-control mb-2 mr-sm-2">
                @foreach($periodos as $per)
                    <option value="{{$per->periodo}}"{{$per->periodo==$periodo_actual->periodo?' selected':''}}>{{$per->identificacion_corta}}</option>
                @endforeach
            </select>
            <label class="sr-only" for="carrera">Carrera</label>
            <select name="carrera" id="carrera" required class="form-control mb-2 mr-sm-2">
                <option value="" selected>--Seleccione--</option>
                @foreach($carreras as $carrera)
                    <option value="{{$carrera->carrera}}">Carrera {{$carrera->nombre_reducido}} Ret ({{$carrera->reticula}})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mb-2">Continuar</button>
        </form>
    </x-information>
@stop
