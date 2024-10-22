@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
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
        <form action="{{route('nodocente.update',['nodocente'=>$nodocente->id])}}" method="post" role="form">
            @csrf
            @method('PATCH')
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Asignación de actividad de apoyo</div>
                            <div class="card-body">
                                <h4 class="card-title">Catálogo de puestos</h4><br>
                                <div class="form-group">
                                    <label for="puesto">Seleccione el puesto a realizar del siguiente listado</label>
                                    <select name="puesto" id="puesto" required class="form-control">
                                        @foreach($puestos as $puesto)
                                            <option value="{{$puesto->clave_puesto}}" {{$puesto->clave_puesto==$nodocente->descripcion_horario?' selected':''}}>{{$puesto->descripcion_puesto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="unidad">Indique el área de quien dependerá dicho puesto (unidad orgánica de adscripción)
                                        <select name="unidad" id="unidad" required class="form-control">
                                            @foreach($areas as $area)
                                                <option value="{{$area->clave_area}}" {{$area->clave_area==$nodocente->area_adscripcion?' selected':''}}>{{$area->descripcion_area}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="observacion">Observaciones para el horario</label>
                                    <p>En caso de requerir alguna observación especial para el horario, favor de indicarlo
                                        en el siguiente recuadro</p>
                                    <textarea name="observacion" id="observacion"
                                              cols="20" rows="5" onblur="this.value=this.value.toUpperCase();"
                                              class="form-control">{{$nodocente->observacion}}</textarea>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                for($i=2;$i<=7;$i++){
                                    switch ($i){
                                        case 2: $dia="Lunes"; $entrada="elunes"; $salida="slunes"; break;
                                        case 3: $dia="Martes"; $entrada="emartes"; $salida="smartes"; break;
                                        case 4: $dia="Miércoles"; $entrada="emiercoles"; $salida="smiercoles"; break;
                                        case 5: $dia="Jueves"; $entrada="ejueves"; $salida="sjueves"; break;
                                        case 6: $dia="Viernes"; $entrada="eviernes"; $salida="sviernes"; break;
                                        case 7: $dia="Sábado"; $entrada="esabado"; $salida="ssabado"; break;
                                    } ?>
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        {{$dia}}
                                    </div>
                                        <?php
                                        $admin=\Illuminate\Support\Facades\DB::table('horarios')->where('periodo',$nodocente->periodo)
                                            ->where('docente',$nodocente->personal)
                                            ->where('consecutivo_admvo',$nodocente->id)
                                            ->where('tipo_horario','Z')
                                            ->where('dia_semana',$i)
                                            ->first();
                                    if(!empty($admin)){?>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$entrada}}" id="{{$entrada}}" value="{{$admin->hora_inicial}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$salida}}" id="{{$salida}}" value="{{$admin->hora_final}}">
                                    </div>
                                    <?php }else{ ?>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$entrada}}" id="{{$entrada}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" name="{{$salida}}" id="{{$salida}}">
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php   } ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-information>
@stop
