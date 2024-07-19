@extends('adminlte::page')

@section('title', 'Estatus del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Actualización de estatus</h4>

                <form action="{{route('rechumanos.estatus_personal')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="estatus">Seleccione del siguiente listado, a fin de
                            modificar su situación</label>
                        <select name="estatus" id="estatus" required class="form-control">
                            @foreach($estatus as $status)
                                <option value="{{$status->estatus}}"
                                    {{$status->estatus==$personal_info->status_empleado?' selected':''}}>
                                    {{$status->descripcion}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

@stop
