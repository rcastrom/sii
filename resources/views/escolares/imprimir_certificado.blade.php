@extends('adminlte::page')

@section('title', 'Certificado')

@section('content_header')
    <h1>Servicios Escolares</h1>
@stop

@section('content')
    <x-information :encabezado="$encabezado">
        <form action="{{route('escolares.certificado_pdf')}}" method="post" role="form" target="_blank">
            @csrf
            <input type="hidden" name="control" value="{{$info['control']}}">
            <input type="hidden" name="director" value="{{$info["director"]}}">
            <input type="hidden" name="registro" value="{{$info["registro"]}}">
            <input type="hidden" name="libro" value="{{$info["libro"]}}">
            <input type="hidden" name="foja" value="{{$info["foja"]}}">
            <input type="hidden" name="fecha_registro" value="{{$info["fregistro"]}}">
            <input type="hidden" name="fecha_emision" value="{{$info["femision"]}}">
            <input type="hidden" name="iniciales" value="{{$info["iniciales"]}}">
            <input type="hidden" name="tipo" value="{{$info["tipo"]}}">
            <input type="hidden" name="autoridad_educativa" value="{{$info["emite_equivalencia"]}}">
            <input type="hidden" name="folio" value="{{$info["equivalencia"]}}">
            <input type="hidden" name="fecha_elaboracion" value="{{$info["fequivalencia"]}}">
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </x-information>
@stop
