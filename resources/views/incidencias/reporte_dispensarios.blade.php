@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Reporte Bitácora de Dispensarios</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@dispensarios_rpt','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf                            
                            
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-4">

                                    <label for="estacion">Estacion</label>
                                    <select id="estacion" name="estacion" class="form-control" required>

                                        @if(\Auth::user()->role == 'admin')
                                            <option value="*" selected>Todas las estaciones</option>
                                        @endif

                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach

                                    </select>
                                    <input type="text" id="aux_sucursal" name="aux_sucursal" hidden/>
                                    <input type="text" id="aux_estacion" name="aux_estacion" hidden/>
                                    <!-- <input type="text" id="aux_fecha1" name="aux_fecha1" hidden/>
                                    <input type="text" id="aux_fecha2" name="aux_fecha2" hidden/> -->

                                </div>                                
                                
                                
                                <div class="form-group col-md-4">
                                    <label>Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha1" name="fecha1" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label>Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha2" name="fecha2" required>
                                </div>
                                
                            </div>                                     
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn_buscar" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Buscar</button>                                    
                                    
                                </div>
                            </div>          
                            

                        @if($msj=="Mostrar")
                            <h5>
                                <div id="sucursal" name="sucursal">{{ $aux_sucursal }}</div>
                                <div id="aux_est" name="aux_est" hidden>{{ $aux_estacion }}</div>
                                <div id="aux_fecha1" name="aux_fecha1" hidden>{{ $aux_fecha1 }}</div>
                                <div id="aux_fecha2" name="aux_fecha2" hidden>{{ $aux_fecha2 }}</div>
                               
                            </h5>
                            <div id="tablaPDF" class="form-row">                            
                                <div class="form-group col-md-12">                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped  table-bordered table-condensed table-hover">
                                             <thead class="thead-light">   <!--style="text-transform: uppercase"-->
                                                <th>Estación</th>
                                                <th>Fecha</th>
                                                <th>Descripción</th>
                                                <th>O. de Trabajo</th>
                                            </thead>
                                            @foreach($consulta as $cc)
                                                <tr>
                                                    <td style="width:10%"> {{ $cc->estacion }} </td>
                                                    <td style="width:15%"> {{ $cc->fecha }} </td>
                                                    <td style="width:65%"> {{ $cc->descripcion }} </td>
                                                    <td style="width:10%">
                                                        <a href="{{ route('orden_trabajo',$cc->orden_ruta) }}" class="btn btn-sm btn-danger" target="_blank" style="width:100px;">
                                                        <i class="far fa-file-pdf fa-1x"></i>&nbsp;Orden</a>                                                    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    
                                    @if($role=="admin")
                                    <div class="form-group form-row">
                                        <div class="ml-auto">
                                            <a href="{{ route('dispensarios_pdf',array($aux_estacion,$aux_fecha1,$aux_fecha2)) }}" class="btn btn-warning">
                                                <i class="fas fa-search fa-1x"></i>&nbsp;Ver Bitácora
                                            </a>
                                        </div>
                                    </div> 
                                    @endif
                                </div>                                                                   
                            </div>
                        @endif
                        {!!Form::close()!!}   
                    </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection


@section('script')
<script>

$(document).ready(function() {
    $('#btn_buscar').click(function() {
        var sucursal = $('select[name=estacion]').find('option:selected').text();
        var estacion = $('select[name=estacion]').val();
        var fecha1 =$('select[name=fecha1]').val();
        var fecha2 =$('select[name=fecha2]').val();
        $("#aux_sucursal").val(sucursal);
        $("#aux_estacion").val(estacion);
        $("#aux_fecha1").val(fecha1);
        $("#aux_fecha2").val(fecha2);
    });
});

</script>
@endsection