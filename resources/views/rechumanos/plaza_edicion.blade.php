@extends('adminlte::page')

@section('title', 'Plazas del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
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
            <form action="{{route('personalPlaza.update',$personalPlaza->id)}}" method="post" role="form">
                @csrf
                @method('PUT')
                <div class="mb-3 row">
                    <label for="unidad" class="col-sm-2 form-label">Unidad</label>
                    <div class="col-sm-10">
                        <input type="text" name="unidad"
                               id="unidad" required class="form-control" value="{{$personalPlaza->unidad}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="subunidad" class="col-sm-2 form-label">Sub unidad</label>
                    <div class="col-sm-10">
                        <input type="text" name="subunidad"
                               id="subunidad" required class="form-control" value="{{$personalPlaza->subunidad}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="categoria" class="col-sm-2 form-label">Categoria</label>
                    <div class="col-sm-10">
                        <select name="categoria" id="categoria" required class="form-control">
                            @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}" {{$categoria->id==$personalPlaza->id_categoria?'selected':''}}>({{$categoria->categoria}}) {{$categoria->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="horas" class="col-sm-2 form-label">Horas</label>
                    <div class="col-sm-10">
                        <input type="number" name="horas" min="1" max="40"
                               id="horas" required class="form-control" value="{{$personalPlaza->horas}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="diagonal" class="col-sm-2 form-label">Diagonal</label>
                    <div class="col-sm-10">
                        <input type="text" name="diagonal"
                               id="diagonal" required class="form-control" value="{{$personalPlaza->diagonal}}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="estatus_plaza" class="col-sm-2 form-label">Estatus de la plaza</label>
                    <div class="col-sm-10">
                        <select name="estatus_plaza" id="estatus_plaza" required class="form-control">
                            <option value="A" {{$personalPlaza->estatus_plaza=='A'?'selected':''}}>Actual</option>
                            <option value="H" {{$personalPlaza->estatus_plaza=='H'?'selected':''}}>Enviar a histórico</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_motivo" class="col-sm-2 form-label">Motivo</label>
                    <div class="col-sm-10">
                        <select name="id_motivo" id="id_motivo" required class="form-control">
                            @foreach($motivos as $motivo)
                                <option value="{{$motivo->id}}" {{$personalPlaza->id_motivo==$motivo->id?'selected':''}} >{{$motivo->motivo.' '.$motivo->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="efectos_iniciales" class="col-sm-2 form-label">Efecto inicial</label>
                    <div class="col-sm-10">
                        <input type="text" name="efectos_iniciales"
                               id="efectos_iniciales" required class="form-control" value="{{$personalPlaza->efectos_iniciales}}" maxlength="6">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="efectos_finales" class="col-sm-2 form-label">Efecto final</label>
                    <div class="col-sm-10">
                        <input type="text" name="efectos_finales"
                               id="efectos_finales" required class="form-control" value="{{$personalPlaza->efectos_finales}}" maxlength="6">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
            </form>
    </x-information>

@stop
