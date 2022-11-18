@extends('layouts.app')

@section('content')

<div class="container">    
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif      
    <div class="row justify-content-center">            
        <div class="col-md-8">                
            <div class="card">
                <div class="card-header">Agregar Nueva Refaccion</div>   
                
                <div class="card-body">
                    {!!Form::open(array('action'=>'IncidenciasController@agregar_refaccion','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Refacción</label>

                            <div class="col-md-6">
                                <input id="descripcion" type="text" 
                                class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" 
                                name="descripcion" required autofocus>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Prioridad</label>

                            <div class="col-md-6">
                                <select id="prioridad" name="prioridad" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="alta">Alta</option>
                                    <option value="media">Media</option>
                                    <option value="baja">Baja</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Área Atención</label>
                            <div class="col-md-6">
                                <select id="area_atencion" name="area_atencion" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Catálogo</label>
                            <div class="col-md-6">
                                <select id="catalogo" name="catalogo" class="form-control" required>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="btn_guardar" name="btn_guardar" onclick="myFunction()">
                                    <i class="fas fa-plus-square fa-1x"></i>&nbsp;Guardar</button>
                            </div>
                        </div>
                    {!!Form::close()!!}
                </div>                   
            </div>                
        </div>            
    </div>        
</div>





@endsection

@section('script')
<script>
    $(document).ready(function()
    {            
        $.get('{{ url("/") }}/areas_atencion',{id: ''},function(data){
            $('#area_atencion').empty();
                $('#area_atencion').append("<option value=''>Selecciona</option>");  
            $.each(data,function(fetch, miobj){
                $('#area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                               
            });
        });
        $.get('{{ url("/") }}/catalogo_refaccion',{},function(data){
            $('#catalogo').empty();
            
            $('#catalogo').append("<option value=''>Selecciona</option>");
            $.each(data,function(fetch, miobj){
                $('#catalogo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
            });
        });
    });

</script>
@endsection