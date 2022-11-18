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
                    <div class="card-header">Reporte Anexo Ventas</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@reporte_anexo_excel','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            
                            
                            <div class="form-group form-row">

                                <div class="form-group col-md-6">
                                <label for="estacion">Estacion: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>

                                    @if ($rol=="admin")
                                    <option value="">Selecciona una estación...</option>
                                    @endif

                                        @foreach($sucursal as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach

                                    </select>
                                    <input type="text" id="aux_sucursal" name="aux_sucursal" hidden>
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label>Selecciona el Mes</label>
                                    <select id="mes" name="mes" class="form-control" required>
                                    <option value="">Selecciona una Mes...</option>
                                        @foreach($meses as $mes)
                                        <option value="{{$mes->MES_NUM}}">{{$mes->MES_LETRA}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="aux_mes" name="aux_mes" hidden>
                                </div>
                                
                            </div>   


                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Reporte</button>                                    
                                </div>
                            </div>
						
                        {!!Form::close()!!}


                        <!--Tabla-->  
                        @if ($rol=="admin")
                            <div class="table-responsive">
                            <h4>Faltan por capturar:&nbsp;{{$cant}}</h4>
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                    <th>#</th>  
                                    <th><i class="fas fa-gas-pump fa-1x"></i>&nbsp;Estacion</th>
                                    <th>Descripcion</th>      
                                </thead>

                                <?php $i=0;?>

                                    @foreach ($medidas_tbl as $med)                                    
                                    <?php $i=$i+1;?>
                                        <tr ><td><?php echo $i;?></td> 
                                            <td>{{ $med->estacion }}</td>                        
                                            <td>{{ $med->nombre_corto  }}</td>                   
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                            @elseif(Auth::user()->id == '64' || Auth::user()->id == '65')
                            @else
                            <div class="table-responsive">
                            <h4>Anexo capturado hoy: &nbsp;<?php echo date("d-m-Y");?></h4>
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                    <!-- <th><i class="fas fa-gas-pump fa-1x"></i>&nbsp;Estacion</th> -->
                                    <th>Dia</th>
                                    <th>Producto</th>
                                    <th>Inv_inicial</th>  
                                    <th>Compras</th>      
                                    <th>Ventas</th>   
                                    <th>Acumulado</th>   
                                    <th>Inv_teórico</th>   
                                    <th>Inv_final</th>    
                                </thead>
                                    @foreach ($medidas_tbl as $med)
                                    <tr > 
                                        <!-- <td>{{ $med->estacion }}</td>                         -->
                                        <td>{{ $med->dia  }}</td>
                                        <td>{{ $med->producto}}</td>
                                        <td>{{ $med->inv_inicial }}</td> 
                                        <td>{{ $med->compra}}</td>    
                                        <td>{{ $med->venta}}</td>     
                                        <td>{{ $med->venta_acum}}</td>     
                                        <td>{{ $med->inv_teorico}}</td>     
                                        <td>{{ $med->inv_final}}</td>                     
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
						



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