@extends('adminlte::page')

@section('title', 'Docentes')

@section('content_header')
    <h1>Jefaturas Académicas</h1>
@stop

@section('content')
    <x-alert>
        @php
            $mensaje="Confirme que desea llevar a cabo la eliminación del siguiente horario";
        @endphp
        @slot('mensaje',$mensaje)
    </x-alert>
    <x-information :encabezado="$encabezado">
        <form action="{{route('nodocente.destroy',['nodocente'=>$nodocente->id])}}" method="post" role="form">
            @csrf
            @method('DELETE')
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Confirme eliminación de horario no docente</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="puesto">Puesto</label>
                                    <input type="text" name="puesto" id="puesto" readonly value="{{$puesto->descripcion_puesto}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="unidad">Área de donde se depende dicho puesto (unidad orgánica de adscripción)</label>
                                    <input type="text" name="unidad" id="unidad" readonly value="{{$area->descripcion_area}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="observacion">Observaciones para el horario</label>
                                    <textarea name="observacion" id="observacion"
                                              cols="20" rows="5" onblur="this.value=this.value.toUpperCase();"
                                              class="form-control" readonly>{{$nodocente->observacion}}</textarea>
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
                                        <input type="time" readonly name="{{$entrada}}" id="{{$entrada}}" value="{{$admin->hora_inicial}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" readonly name="{{$salida}}" id="{{$salida}}" value="{{$admin->hora_final}}">
                                    </div>
                                    <?php }else{ ?>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" readonly name="{{$entrada}}" id="{{$entrada}}">
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <input type="time" readonly name="{{$salida}}" id="{{$salida}}">
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php   } ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-information>
@stop
