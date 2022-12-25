@extends('adminlte::page')

@section('title', 'Actualización personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h5>Modificación de {{$titulo}}</h5>
                <form action="" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <label for="old" class="col-sm-4 col-form-label">Valor actual</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="old" id="old" value="{{$valor}}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="new" class="col-sm-4 col-form-label">Nuevo valor</label>
                        <div class="col-sm-8">
                            @if($campo_editar!=7)
                                <input type="text" name="new" id="new" class="form-control">
                            @else
                                <select name="new" id="new" class="form-control" required>
                                    <option value="" selected>--Seleccione--</option>
                                    @foreach($areas as $area)
                                        @php
                                        $txt=$area->clave_area==$personal_info->clave_area?" selected":"";
                                        @endphp
                                        <option value="{{$area->clave_area}}"{{$txt}}>{{$area->descripcion_area}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                    <input type="hidden" name="personal" value="{{$id_temp}}">
                    <input type="hidden" name="campo" value="{{$campo_editar}}">
                </form>
            </div>
        </div>
    </x-information>
@stop
