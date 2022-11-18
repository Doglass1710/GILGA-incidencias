@extends('layouts.app')

@section('content')
<!-- <img src="{{ asset('images/logo_gilga.png') }}" alt="Logo" height="61" width="120"/> -->
                
<div class="container">        
    <div class="row justify-content-center">            
        <div class="col-md-8">                
            <div class="card">
                <div class="card-header">Reportes PDF de descarga SISA Firmados</div>                    
                <div class="card-body">
                {!!Form::open(array('action'=>'IncidenciasController@firmados_sisa','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf

                    <div class="form-row">  

                        <div class="form-group col-md-4">

                            <label>Estación: </label>                                    
                            <select id="estacion" name="estacion" class="form-control" required>
                                <option value="*" selected>Todas las estaciones</option>                              
                            @foreach($estaciones as $estacion)                                        
                                <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>   
                            @endforeach                                     
                            </select>
                            <input type="text" id="aux_sucursal" name="aux_sucursal" hidden/>

                        </div>

                        <div class="form-group col-md-4">
                            <label>Desde: </label> 
                            <input type="date" class="form-control" id="fecha" name="fecha"/> 
                        </div>

                        <div class="form-group col-md-4">
                            <label>Hasta: </label> 
                            <input type="date" class="form-control" id="fecha2" name="fecha2"/> 
                        </div>

                    </div>

                    <div class="form-group form-row">   

                        <div class="ml-auto">
                            <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                            <button type="submit" id="btn_buscar" class="btn btn-primary"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>                                    
                        </div>
                        
                    </div>

                                       
                    @if($msj=="Mostrar")
                    <h5><div id="sucursal" name="sucursal">{{ $aux_sucursal }}&nbsp;al&nbsp;{{ $fecha }}</div></h5>
                    <div id="tablaPDF" class="form-row">                            
                        <div class="form-group col-md-12">
                   
                        <table class="table table-striped  table-bordered table-condensed table-hover">
                            <thead class="thead-light" style="text-transform: uppercase">                                        
                                <th>Estación</th>
                                <th>Factura</th>
                                <th>Producto</th>
                                <th>Fecha</th>
                                <th>Ver PDF</th>
                            @foreach($consulta_sisa as $ss)
                                <tr>
                                <td>{{ $ss->estacion }}</td>
                                <td>{{ $ss->factura }}</td>
                                <td>{{ $ss->producto }}</td>
                                <td>{{ $ss->fecha }}</td>
                                <td> 
                                    <a href="{{ route('firmados_visualizar',$ss->archivopdf) }}" class="btn btn-sm btn-danger" target="_blank" style="width:100px;">
                                    <i class="far fa-file-pdf fa-1x"></i>&nbsp;VerPDF</a> </td>
                                </tr>
                            @endforeach
                            </thead>
                        <table>
                        </div>
                    </div>
                    @endif

                    <div class="form-group form-row">
                        
                        <div class="ml-auto">
                        <a class="btn btn-success" href="{{ url('reporte_sisa') }}">
                            <i class="fas fa-hand-point-left fa-1x"></i>&nbsp;Volver
                        </a>                            
                        </div>

                    </div>
                {!!Form::close()!!}   
               
            </div>
        </div>
    </div>
</div> 
@endsection

@section('script')
<script>
$(function(){
    $('#estacion').change(event => {
        $("#tablaPDF").css("display", "none");
        $("#sucursal").html("");
    });
});

$(document).ready(function() {
    $('#btn_buscar').click(function() {
        var sucursal = $('select[name=estacion]').find('option:selected').text();
        $("#aux_sucursal").val(sucursal);
    });
});
</script>
@endsection