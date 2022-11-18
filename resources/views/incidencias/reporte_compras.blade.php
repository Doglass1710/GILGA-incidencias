@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Reporte Compras</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@genReporteCompras','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf
                            
                            
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Tipo de Reporte</label>
                                    <select id="tipo" name="tipo" class="form-control" required>
                                        <option value="1">Original</option>
                                        <option value="2">Incidencias con Compras</option>
                                        <option value="3">Incidencias sin Compras (Operaciones)</option>
                                    </select>
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

