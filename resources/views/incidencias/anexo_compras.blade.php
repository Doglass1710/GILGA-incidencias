@extends('layouts.app')

@section('content')

    <div class="container"> 
    <div class="alert alert-success" id="divmsj">
        {{ $message }}
    </div> 
        <div class="row justify-content-center">            
            <div class="col-md-8">   
                        {!!Form::open(array('action'=>'IncidenciasController@anexo_compras_guardar','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{url('incidencias')}}" enctype="multipart/form-data">-->
                            @csrf             
                <div class="card">
                    <div class="card-header"><h5>Anexo Compras</h5></div>                       
                    <div class="card-body">
                        <div class="form-group form-row">
                            <div class="form-group col-md-2">
                                <label>Estación</label>
                                <select id="estacion" name="estacion" class="form-control">
                                    @foreach($estaciones as $est)
                                        <option value="{{ $est->estacion }}">{{ $est->estacion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Tanque</label>
                                <input id="tanque" name="tanque" type="number" min="1" max="10" class="form-control" value="1" />
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label>Num. Eco.</label>                                  
                                <input type="text" class="form-control" id="num_eco" name="num_eco" required>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Nombre del Operador</label>
                                <input type="text" class="form-control" id="operador" name="operador" required>                                    
                            </div>
                        </div>   


                        <div class="card">
                            <div class="card-header"><b>Litros S/G Factura</b></div>
                                <div class="card-body">
                                        
                                    <div class="form-group form-row">                                
                                        
                                        <div class="form-group col-md-3">
                                            <label>Fecha</label>
                                            <input id="fecha" name="fecha" type="date" min="2021-01-01" class="form-control"  required/>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label>Importe</label>
                                            <input type="text" id="importe" name="importe"  class="form-control"  placeholder="$ 00.00" required/>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label>Producto</label>
                                            <select id="producto" name="producto" class="form-control" required>
                                                <option>Magna</option>
                                                <option>Premium</option>
                                                <option>Diesel</option>
                                            </select>                                                                            
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Litros</label>
                                            <input type="text" id="litros" name="litros"  class="form-control"  required/>
                                        </div>
                                        
                                    </div>

                                    <div class="form-group form-row">                                
                                        
                                        <div class="form-group col-md-3">
                                            <label>Folio</label>
                                            <input type="text" id="folio" name="folio"  class="form-control"  required/>                                        
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label>Inicia</label>
                                            <input type="time" class="form-control" id="inicia" name="inicia" required/>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label>Termina</label>
                                            <input type="time" class="form-control" id="termina" name="termina" required/>                                                                            
                                        </div>

                                        <div class="form-group col-md-3">
                                            <div id="div_cubetas" style="display:none">
                                                <label>Cubetas (19 lts):</label>
                                                <input type="text" class="form-control" id="cubetas" name="cubetas"/>
                                            </div>
                                        </div>                                       
                                        
                                    </div>
                                
                                </div>
                            </div>
                            <div class="card"><div class="card-header"><b>Litros según Veeder Root</b></div>
                                <div class="card-body">
                                    <div class="form-group form-row">

                                        <div class="form-group col-md-3">
                                        <label>Vol. Inicial</label>
                                        <input type="text" id="vinicial" name="vinicial"  class="form-control" required/>
                                        </div>
                                        <div class="form-group col-md-3">
                                        <label>Vol. Final</label>
                                        <input type="text" id="vfinal" name="vfinal"  class="form-control" required/>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                        <label>Ventas de descarga</label>
                                        <input type="text" id="venta" name="venta"  class="form-control" />
                                        </div>
                                        <div class="form-group col-md-3" hidden>
                                        <label>Sobrante/faltante</label>
                                        <input type="text" id="sobrante" name="sobrante"  class="form-control"/>
                                        </div>

                                    </div>
                                </div>
                            </div>
                                         <br/>

                                    <div class="form-group form-row">
                                        <div class=" ml-auto">                                            
                                            <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                        </div>
                                    </div>         
                                                        
                             
                        </div>                    
                    </div>
                        {!!Form::close()!!}
                                        
            </div>            
        </div>        
    </div>

@endsection

@section('script')
<script>

$(function myFunction() {  
    var msj =$("#divmsj").html();
    if (msj=="")    
    {
        $("#divmsj").css("display", "none"); 
    }else
        {
            // $("#btn").prop('disabled', true);                    
            $("#divmsj").css("display", "block");            
        }
});

$(function(){

    $('#tanque,#importe,#litros,#vinicial,#vfinal,#vdescarga,#venta,#cubetas').keypress(function(e) {
        if(isNaN(this.value + String.fromCharCode(e.charCode))) 
        return false;
        })
        .on("cut copy paste",function(e){
        e.preventDefault();
    });

    var estacion =$('select[name=estacion]').val();
    if(estacion=="6571")
    {
        $('#div_cubetas').css("display","block");
    }

    $('#estacion').change(event => {
        var estacion =$('select[name=estacion]').val();
        if(estacion=="6571")
        {
            $('#div_cubetas').css("display","block");
        }else{
            $('#div_cubetas').css("display","none");
        }
    });

});

</script>
@endsection






