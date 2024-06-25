@extends('adminlte::page')

@section('title', 'Fichas')

@section('content_header')
    <h1>Departamento de Desarrollo Académico</h1>
@endsection

@section('content')
    <x-information :encabezado="$encabezado">
        Indique los parámetros necesarios para el período de entrega de fichas
        <form action="{{route('desarrollo.fichas_inicio')}}" method="post" role="form">
            @csrf
            <fieldset>
                @if($bandera)
                    <div class="form-group">
                        <label for="fichas">Período para aspirantes a ingresar</label>
                        <select name="fichas" id="fichas" class="form-control">
                            @foreach($periodos as $per)
                                <option value="{{$per->periodo}}"{{$per->periodo==$periodos_ficha->fichas?' selected':''}}>{{$per->identificacion_corta}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activo">¿Continúa siendo éste período para entrega de fichas?</label>
                        <select name="activo" id="activo" class="form-control">
                            <option value="1" selected>Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="entrega">Indique la fecha de inicio para la entrega de fichas</label>
                        <input type="date" name="entrega" id="entrega"
                               required class="form-control" value="{{$periodos_ficha->entrega}}">
                    </div>
                    <div class="form-group">
                        <label for="termina">Indique la fecha de término para la entrega de fichas</label>
                        <input type="date" name="termina" id="termina"
                               required class="form-control" value="{{$periodos_ficha->termina}}">
                    </div>
                    <div class="form-group">
                        <label for="inicio_prope">Indique la fecha de inicio del curso propedéutico</label>
                        <input type="date" name="inicio_prope" id="inicio_prope"
                               required class="form-control" value="{{$periodos_ficha->inicio_prope}}">
                    </div>
                    <div class="form-group">
                        <label for="fin_prope">Indique la fecha de término del curso propedéutico</label>
                        <input type="date" name="fin_prope" id="fin_prope"
                               required class="form-control" value="{{$periodos_ficha->fin_prope}}">
                    </div>
                @else
                    <div class="form-group">
                        <label for="fichas">Período para aspirantes a ingresar</label>
                        <select name="fichas" id="fichas" class="form-control" required>
                            @foreach($periodos as $per)
                                <option value="{{$per->periodo}}">{{$per->identificacion_corta}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activo">¿Es el valor por omisión?</label>
                        <select name="activo" id="activo" class="form-control" required>
                            <option value="" selected>--Indique--</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="entrega">Indique la fecha de inicio para la entrega de fichas</label>
                        <input type="date" name="entrega" id="entrega"
                               required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="termina">Indique la fecha de término para la entrega de fichas</label>
                        <input type="date" name="termina" id="termina"
                               required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="inicio_prope">Indique la fecha de inicio del curso propedéutico</label>
                        <input type="date" name="inicio_prope" id="inicio_prope"
                               required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fin_prope">Indique la fecha de término del curso propedéutico</label>
                        <input type="date" name="fin_prope" id="fin_prope"
                               required class="form-control">
                    </div>
                @endif
            </fieldset>
            <input type="hidden" name="bandera" value="{{$bandera}}">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </x-information>
@endsection
