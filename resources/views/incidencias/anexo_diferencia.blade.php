@extends('layouts.app')

@section('content')

    <div class="container">  
    <div class="alert alert-success" id="divmsj">
        {{ $message }}
    </div>
        <div class="row justify-content-center">            
            <div class="col-md-8">   
                        {!!Form::open(array('action'=>'IncidenciasController@anexo_diferencia_guardar','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf             
                

                        <div class="card">
                            <div class="card-header"><b>Diferencias</b></div>
                                <div class="card-body">
                                        
                                    <div class="form-group form-row">                                
                                        
                                        <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input id="fecha" name="fecha" type="date"  class="form-control"  required/>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                        <label>Turno</label>
                                        <input type="number" id="turno" name="turno" min="1" max="20" class="form-control" value="1" required/>                                                              
                                        </div>

                                        <div class="form-group col-md-3">
                                        <label>Dif. de dinero $</label>
                                        <input type="text" id="litros" name="litros"  class="form-control"  required/>
                                        </div>                                        
                                        
                                        <div class="form-group col-md-3">
                                        <label>Acumulado</label>
                                        <input type="text" id="acumulado" name="acumulado"  class="form-control" required/>              
                                        </div>
                                        
                                    </div>

                                    <div class="form-group form-row">  

                                        <!-- <div class="form-group col-md-3">
                                        <label>Diferencia</label>
                                        <label id="dif" name="dif">s</label>                                        
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                        <label>Total</label>
                                        <label id="total" name="total">a</label>                                       
                                        </div>                               -->
                                        
                                        <div class="form-group col-md-8">                                     
                                        </div>
                                        
                                        <div class="form-group col-md-4" style="text-align:right"> 
                                        <!-- <div class=" ml-auto">                                             -->
                                            <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                        <!-- </div>                                      -->
                                        </div>
                                        
                                    </div>
                                
                                         <br/>

                                    <!-- <div class="form-group form-row">
                                        <div class=" ml-auto">                                            
                                            <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                        </div>
                                    </div>  -->

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
            //$("#btn").prop('disabled', true);                    
            $("#divmsj").css("display", "block");            
        }
});

$(function(){

$('#litros,#acumulado,#turno').keypress(function(e) {
if(isNaN(this.value + String.fromCharCode(e.charCode))) 
    return false;
})
.on("cut copy paste",function(e){
    e.preventDefault();
});

});
</script>
@endsection






