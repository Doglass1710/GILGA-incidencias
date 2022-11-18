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
                    <div class="card-header">Reporte Plantilla Inventario</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@anexo_inventario','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            
                            
                            <div class="form-group form-row">

                                <div class="form-group col-md-4">
                                <label for="estacion">Estacion: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>

                                        <option value="">Selecciona una estación...</option>
                                        <option value="*">TODAS LAS ESTACIONES</option>
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach

                                    </select>
                                    <input type="text" id="aux_sucursal" name="aux_sucursal" hidden>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" name="fecha_desde" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta" required>
                                </div>
                            
                                <!-- <div class="form-group col-md-6">
                                    <label>Selecciona el Mes</label>
                                    <select id="mes" name="mes" class="form-control" required>
                                    <option value="">Selecciona una Mes...</option>
                                       
                                    </select>
                                    <input type="text" id="aux_mes" name="aux_mes" hidden>
                                </div> -->
                                
                            </div>   


                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Reporte</button>                                    
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
 $(document).ready(function() {
    $('#estacion').change(event => {
        var sucursal = $('select[name=estacion]').find('option:selected').text();
        $("#aux_sucursal").val(sucursal);
        });

    $('#mes').change(event => {
        var mes = $('select[name=mes]').find('option:selected').text();
        $("#aux_mes").val(mes);
        });
    });
</script>
@endsection