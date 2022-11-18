@extends('layouts.app')

@section('content')

    <div class="container">  
        @if(session('status'))
        <div id="lbl_msj" class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header" onload="myFunction()">
                        <h5>Anexo Ventas - Borrar capturas</h5>
                        
                    </div>   
                    
                    <div class="card-body" >
                        {!!Form::open(array('method'=>'POST', 'action' =>'IncidenciasController@anexo_borrar_ejecutar' ,'autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Estación: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        @if(\Auth::user()->role == 'admin')
                                            <option value="">Selecciona Estacion...</option>   
                                        @endif 
                                        @foreach($estaciones as $estacion)
                                            <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach
                                    </select>                               
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Hoja: </label>
                                    <select id="tabla" name="tabla" class="form-control" required>
                                        <option value="1">Anexo_Ventas</option>
                                        <!--<option value="2">Anexo_Compras</option>
                                         <option value="3">Anexo_Diferencias</option> -->
                                    </select>  
                                </div>
                                <div class="form-group col-md-4">
                                    <!-- <label>Fechas: </label>
                                    <input type="date" 
                                        class="form-control" 
                                        id="fecha" name="fecha"                                         
                                        min="2021-01-01" max="<?php echo date('Y-m-d',strtotime('-1 day'));?>" required />      -->
                                    <label>Días: </label>
                                    <select id="dias" name="dias" class="form-control" required>
                                    
                                    </select>  
                                </div>                               
                                    
                            </div>

                            <div class="form-group form-row">

                                <div class=" ml-auto"> 
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-danger"><i class="fas fa-trash fa-1x"></i>&nbsp;Borrar captura</button>                                    
                                </div>
                            </div>  

                        {!!Form::close()!!}
                    
                    </div> 
                </div>  
                <br/>
                <div class="card">
                    <div class="card-header">
                        <h5>Anexo Compras - Borrar</h5>                        
                    </div>   
                    
                    <div class="card-body" >
                        {!!Form::open(array('method'=>'POST', 'action'=>'IncidenciasController@anexo_compras_consultar' ,'autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Estación: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        @if(\Auth::user()->role == 'admin')
                                            <option value="">Selecciona Estacion...</option>   
                                        @endif 
                                        @foreach($estaciones as $estacion)
                                            <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach
                                    </select>                               
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Hoja: </label>
                                    <select id="tabla" name="tabla" class="form-control" required>
                                        <option value="1">Anexo_Compras</option>
                                    </select>  
                                </div>
                                <div class="col-md-4">
                                    <label>Fecha</label>
                                    <input id="fecha" name="fecha" type="date" min="2021-01-01" class="form-control"  required/>
                                </div>                               
                                    
                            </div>

                            <div class="form-group form-row">

                                <div class=" ml-auto"> 
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn_compras" name="btn_compras" class="btn btn-warning"><i class="fas fa-search fa-1x"></i>&nbsp;Consultar</button>                                    
                                </div>
                            </div>  

                        {!!Form::close()!!}
                                   
                            <div class="form-group form-row">
                                <!--Tabla-->
                                <div class="table-responsive" id="div_tabla">
                                <table id="myTable" class="table table-striped table-bordered table-condensed table-hover" 
                                    style="font-size: 13px; padding: 0px;">
                                    <thead class="table table-bordered">
                                    <th>Estacion</th>
                                    <th>ID</th> 
                                    <th>No_eco</th>
                                    <th>Fecha</th>
                                    <th>Importe</th>  
                                    <th>Producto</th> 
                                    <th>Litros</th> 
                                    <th>FolioPMX</th>    
                                    <th></th>   
                                </thead>
                                    @foreach ($consulta as $cc)
                                    <tr > 
                                        <td>{{ $cc->estacion }}</td> 
                                        <td>{{ $cc->id }}</td>                       
                                        <td>{{ $cc->no_eco  }}</td>
                                        <td>{{ $cc->fecha }}</td>
                                        <td>{{ $cc->importe }}</td> 
                                        <td>{{ $cc->producto }}</td>
                                        <td>{{ $cc->litros }}</td> 
                                        <td>{{ $cc->folioPMX }}</td>  
                                        <td>      
                                        {!!Form::open(array('action'=>'IncidenciasController@anexo_compras_eliminar','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                                            {{Form::token()}}
                                                @csrf
                                            <input type="text" name="txt_id" value="{{ $cc->id }}" hidden/>
                                            <button type="submit" class="btn btn-sm btn-danger" id="btn_borrar_compra">
                                            <i class="fas fa-trash fa-1x"></i>&nbsp;Borrar</button>  
                                        {!!Form::close()!!}  
                                        </td> 
                                        <!-- <td>      
                                        
                                            <input type="text" name="txt_id_modificar" value="{{ $cc->id }}" hidden/>
                                            <button type="submit" class="btn btn-sm btn-warning" id="btn_modificar_compra">
                                            <i class="fas fa-trash fa-1x"></i>&nbsp;Editar</button>  
                                       
                                        </td>                    -->
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                    </div>
                    
                </div> 
            </div>                
        </div>            
    </div>        
</div>

@endsection

@section('script')
<script>
    $(function myFunction(){
        var estacion = $('select[name=estacion]').val();

        $.get('{{ url("/") }}/dias_anexo',{estacion: estacion},function(data){
            $('#dias').empty();
            $.each(data,function(fetch, miobj){
                $('#dias').append('<option value="' + miobj.fecha_diavencido + '">' + miobj.fecha_diavencido + '</option>');                
            });
        });
    });

    $(function (){
         $('#btn').click(function(){
           $("#lbl_msj").html("");
            //$existe=$("#fecha").val();
            $existe=$('select[name=dias]').val();

            if($existe=='null')
            {
                $('#estacion').focus();
                hasError = true;
            } else
            {
                $opcion=confirm("¿Estas seguro de borrar la captura de este día? " + $existe);
                if($opcion == true){
                    //alert("Se ha realizado el POST con exito ");
                    hasError = false;
                
                }else{
                    //alert("cancelar");
                    hasError = true;
                }
            }              

            if(hasError) event.preventDefault();
        });

     //Evento change    
        $('#estacion').change(event => {
            var estacion = $('select[name=estacion]').val();
            $("#lbl_msj").html("");

            $.get('{{ url("/") }}/dias_anexo',{estacion: estacion},function(data){
                $('#dias').empty();
                $.each(data,function(fetch, miobj){
                    $('#dias').append('<option value="' + miobj.fecha_diavencido + '">' + miobj.fecha_diavencido + '</option>');                
                });
            });
        });


        //BORRAR COMPRAS
        $('#btn_borrar_compra').click(function(){
            $("#lbl_msj").html("");
            $opcion=confirm("¿Estas seguro de borrar la captura de esta compra? ");
            if($opcion == true){
                hasError = false;
                }
            else{
                hasError = true;
                } 
            if(hasError) event.preventDefault();
        });


    });
</script>
@endsection