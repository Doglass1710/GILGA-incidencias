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
                    <div class="card-header">Reporte Medidas Diarias</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@genReporteMedidas','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf
                            
                            <div class="form-group form-row">
                                <div class="form-group col-md-4">
                                    <input type="radio" id="solo_medidas" name="tipo_rpt" value="solo_medidas" checked required>
                                    <label for="solo_medidas">Sólo Medidas</label>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="radio" id="con_ventas" name="tipo_rpt" value="con_ventas" required>
                                    <label for="con_ventas">Con Ventas</label>
                                </div>
                            </div>
                            
                            <div class="form-group form-row">

                                <div class="form-group col-md-4">
                                <label for="estacion">Estacion: </label>
                                  <select id="estacion" name="estacion" class="form-control" required>
                                  @if ($rol=="admin")
                                  <option value="*" selected>Todas las estaciones</option>
                                  @endif
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" name="fecha_desde" value="<?php echo date("d/m/Y");?>">
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta" value="<?php echo date("d/m/Y");?>">
                                </div>
                                
                            </div>   


                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Reporte</button>                                    
                                </div>
                            </div>
 <!--Tablas-->  
                            @if ($rol=="admin")
                            <div class="table-responsive">
                            <h4>Faltan por capturar:&nbsp;{{$cant}}</h4>
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                    <th><i class="fas fa-gas-pump fa-1x"></i>&nbsp;Estacion</th>
                                    <th>Descripcion</th>      
                                </thead>
                                    @foreach ($medidas_tbl as $med)
                                    <tr > 
                                        <td>{{ $med->estacion }}</td>                        
                                        <td>{{ $med->descripcion  }}</td>                   
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                    <th><i class="fas fa-gas-pump fa-1x"></i>&nbsp;Estacion</th>
                                    <th>Magna</th>
                                    <th>Premium</th>
                                    <th>Diesel</th>  
                                    <th>Fecha</th>       
                                </thead>
                                    @foreach ($medidas_tbl as $med)
                                    <tr > 
                                        <td>{{ $med->estacion }}</td>                        
                                        <td>{{ $med->magna  }}</td>
                                        <td>{{ $med->premium}}</td>
                                        <td>{{ $med->diesel }}</td> 
                                        <td>{{ $med->fecha_aplica}}</td>                    
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
						
                        
                        <!--</form>-->
                        {!!Form::close()!!}
                    </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection

@section('script')
<script>

$(function(){
    var d = new Date();
    $('#fecha_desde').append(d.getDate());
    $('#fecha_hasta').append(d.getDate());
    //document.getElementById("fecha_desde").innerHTML =d.getDate()
});

</script>
@endsection