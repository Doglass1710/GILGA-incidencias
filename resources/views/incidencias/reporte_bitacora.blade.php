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
                    <div class="card-header">Reporte Bitácora</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@reporte_bitacora_excel','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf
                            
                            
                            <div class="form-group form-row">

                                <div class="form-group col-md-4">
                                <label for="estacion">Estacion: </label>
                                  <select id="estacion" name="estacion" class="form-control" required>
                                        @foreach($sucursal as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" name="fecha_desde" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta" required>
                                </div>
                                
                            </div>   


                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
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
