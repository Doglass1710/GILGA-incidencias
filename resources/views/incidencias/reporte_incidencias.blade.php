@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Reporte Incidencias</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@genReporte','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf
                                                          
                                <!-- <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="ant" name="tipo_rpt" value="ant" checked required>
                                    <label class="form-check-label">Listado Anterior</label>
                                </div> 
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="nvo" name="tipo_rpt" value="nvo" required>
                                    <label class="form-check-label">Nuevo Listado</label>
                                </div>                                
                            
                            <div class="form-group form-row">  </div>  -->

                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-4">
                                    @if(\Auth::user()->role == 'admin')
                                        <label for="estacion">Estacion</label>
                                        <select id="estacion" name="estacion" class="form-control" required>
                                            <option value="*" selected>Todas las estaciones</option>
                                            @foreach($estaciones as $estacion)
                                            <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <label for="estacion">Estacion</label>
                                        <select id="estacion" name="estacion" class="form-control" required>
                                            <option value="" selected>Selecciona Estacion...</option>
                                            @foreach($estaciones as $estacion)
                                            <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    
                                </div>                                
                                
                                
                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
                                </div>
                                
                            </div>                                     
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <a href="/listado_incidencias"><button type="button" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Reporte</button>                                    
                                </div>
                            </div>                            
                            
                        <!--</form>-->
                        {!!Form::close()!!}
                        
                    </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection

