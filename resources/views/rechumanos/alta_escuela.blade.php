@extends('adminlte::page')

@section('title', 'Estudios del personal')

@section('content_header')
    <h1>Recursos Humanos</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h4>Actualización de institución educativa</h4>
                <p>Emplee el siguiente formulario para dar de alta una institución educativa no enlistada en la
                    sección anterior, para estudios del personal</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('rechumanos.alta_escuela')}}" method="post" class="row">
                    @csrf
                    <div class="col-12">
                        <label for="estado" class="form-label">Estado de la República Mexicana</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="" selected>--Seleccione--</option>
                            @foreach($estados as $estado)
                                <option value="{{$estado->entidad_federativa}}">{{$estado->nombre_entidad}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="municipio" class="form-label">Municipio</label>
                        <select name="municipio" id="municipio" class="form-control" required>
                            <option value="" selected>--Seleccione--</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre de la institución</label>
                        <input type="text" name="nombre" id="nombre" required
                               class="form-control" onchange="this.value=this.value.toUpperCase();">
                    </div>
                    <input type="hidden" name="estudios" id="estudios" value="{{$estudio}}">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </x-information>

    <x-additional>
        @slot('header','Acciones adicionales')
        <div class="row">
            <ul>
                <li><a href="/rechumanos/personal/alta_municipio/{{ $estudio }}">Dar de alta un nuevo municipio</a></li>
            </ul>
        </div>

    </x-additional>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        $(document).ready(function (){
            $('#estado').change(function (){
                //Se obtiene al estado
                var estado = $(this).val();
                //Resetear municipios
                $('#municipio').find('option').not(':first').remove();
                //Se hace la petición
                $.ajax({
                    url:'/rechumanos/personal/municipios/'+estado,
                    type:'get',
                    dataType: 'json',
                    success: function (response) {
                        var len = 0;
                        if(response['data']!=null){
                            len=response['data'].length;
                        }
                        if(len>0){
                            //Se crean los options correspondientes
                            for (var i=0; i<len; i++){
                                var id = response['data'][i].id;
                                var name = response['data'][i].municipio;
                                var option = "<option value='"+id+"'>"+name+"</option>";
                                $("#municipio").append(option);
                            }
                        }
                    }
                });
            });
        });
    </script>
@stop
