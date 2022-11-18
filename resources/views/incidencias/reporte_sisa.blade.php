@extends('layouts.app')

@section('content')
    <div class="container">    
    <div class="alert alert-success" id="divmsj" hidden>
        {{ $msj }}
    </div>
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Reporte de descarga SISA</div>     
                                   
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@reporte_sisa','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf

                    <div class="form-row">        

                        <div class="form-group col-md-4">
                            <label>Estación: </label>                                    
                            <select id="estacion" name="estacion" class="form-control" required>

                                @if ($role=="admin")
                                    <option value="*" selected>Todas las estaciones</option>  
                                @endif
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

                    <div class="form-row">                        
                        <div class="ml-auto">
                        @if ($role=="admin")
                            <a href="{{ route('firmados_sisa') }}" class="btn btn-warning">
                                <i class="fas fa-search fa-1x"></i>&nbsp;Ver PDF Firmados
                            </a>    
                        @endif
                            <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                            <button type="submit" id="btn_buscar" class="btn btn-primary"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>                                    
                        </div>
                    </div>
                    <br/><br/>
                    
                @if($msj=="Mostrar")
                <h5><div id="sucursal" name="sucursal">{{ $aux_sucursal }}&nbsp;al&nbsp;{{ $fecha }}</div></h5>
                    <div id="tablaPDF" class="form-row">                            
                        <div class="form-group col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="thead-light" style="text-transform: uppercase">                                        
                                        <th>Estación</th>
                                        <!-- <th>Factura</th> -->
                                        <th>Producto</th>
                                        <th>Fecha</th>
                                        <th>Ver PDF</th>
                                        <th>Subir PDF Firmado</th>
                            @foreach($consulta as $cc)
                                        <tr>
                                            <td style="width:10%"> {{ $cc->estacion }} </td>
                                            <!-- <td> {{ $cc->factura }} </td> -->
                                            <td style="width:15%"> {{ $cc->producto }} </td>
                                            <td style="width:20%"> {{ $cc->fecha }} </td>
                                            <td style="width:10%">
                                                <a href="{{ route('pdf_sisa',$cc->id) }}" class="btn btn-sm btn-danger" target="_blank" style="width:100px;">
                                                <i class="far fa-file-pdf fa-1x"></i>&nbsp;PDF</a>
                                            </td>
                                            <td style="width:40%">
                        {!!Form::close()!!}
                                            {!!Form::open(array('action'=>'IncidenciasController@subir_pdf','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                                            {{Form::token()}}
                                                @csrf
                                                <input type="text" name="txt_id" value="{{ $cc->id }}" hidden/>
                                                <input type="file" accept=".pdf" style="width:65%;" id="pdf_file" name="pdf_file" />
                                                <!-- <a href="{{ route('subir_pdf',$cc->id) }}" class="btn btn-sm btn-success" target="_blank" style="width:80px;">
                                                <i class="far fa-file-pdf fa-1x"></i>&nbsp;Subir</a> -->
                                                <button type="submit" class="btn btn-sm btn-success" style="width:30%;">
                                                <i class="far fa-file-pdf fa-1x"></i>&nbsp;Subir</button> 
                                            {!!Form::close()!!}

                                            </td>
                                        </tr>
                            @endforeach
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                @endif

<br/>
                            <!-- <img src="{{ url('/') }}/sisa/16203211332.png" style="width:100px;height:90px;"> -->
                            

						
                        
                    </div>                    
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