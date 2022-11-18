@extends('layouts.app')

@section('content')

    <div class="container">  

    <div class="alert" id="divmsj">
        {{ $message }}
    </div>
       <input type="text" id="lbl_msj" value="{{ $message }}" hidden/>
    
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header" onload="myFunction()"><h5>Anexo - Ventas</h5>
                    
                    </div>   
                    
                    <div class="card-body" >
                        {!!Form::open(array('method'=>'POST', 'action' =>'IncidenciasController@anexo_ventas_guardar' ,'autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">

                                    <label>Estación: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        @if($role=="admin")
                                        <option value="" selected>Selecciona Estacion...</option>   
                                        @endif 

                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach
                                    </select>
                               
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <!-- onchange="handler(event);" -->
                                    <label>Fecha: </label>
                                    <input type="date" 
                                        class="form-control" 
                                        id="fecha" name="fecha"                                         
                                        min="2021-01-01" max="<?php echo date('Y-m-d',strtotime('-1 day'));?>" required />                                    
                                    <input type="text" id="rol" name="rol" value="{{$role}}" hidden/>
                                </div>


                            </div>
                            

                            <!--Pequeños encabezados-->
                            <div class="row">
                                <div class="col-md-4">
                                <label></label>
                                </div>
                                <!-- <div class="col-md-3">
                                <label>Inv. Inicial</label>
                                </div> -->
                                <div class="col-md-4">
                                <label>Compras</label>
                                </div>
                                <div class="col-md-4">
                                <label>Ventas</label>
                                </div>
                            </div>

                            

                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="Magna" class="btn btn-success" style="width:100%;">
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Magna</label>
                                </div>
                                <!-- <div class="col-md-3">
                                    <label class="form-control" id="lbl_medidaMagna"></label>
                                </div>  -->
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="txt_compraMagna" name="txt_compraMagna" required maxlength="50">
                                </div> 
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="txt_ventaMagna" name="txt_ventaMagna" required maxlength="50">
                                </div> 
                            </div>
                            
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="Premium" class="btn btn-danger" style="width:100%;">
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Premium</label>
                                </div>
                                <!-- <div class="col-md-3">
                                    <label class="form-control" id="lbl_medidaPremium"></label>
                                </div>            -->
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="txt_compraPremium" name="txt_compraPremium" required maxlength="50">
                                </div>     
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="txt_ventaPremium" name="txt_ventaPremium" required maxlength="50">
                                </div>                                  
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">        
                                    <label for="Diesel" class="btn btn-primary" style="background-color:black;width:100%;" disabled>
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Diesel</label>                   
                                </div>
                                <!-- <div class="form-group col-md-3">
                                    <label class="form-control" id="lbl_medidaDiesel"></label>
                                </div>   -->
                                <div class="form-group col-md-4">
                                    <input type="text" class="form-control" id="txt_compraDiesel" name="txt_compraDiesel" required maxlength="50">
                                </div> 
                                <div class="form-group col-md-4">
                                    <input type="text" class="form-control" id="txt_ventaDiesel" name="txt_ventaDiesel" required maxlength="50">
                                </div> 
                            </div>                        
                            
                            <div class="form-group form-row">

                                <div class=" ml-auto"> 
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
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

@section ('script')
<script>
$(function myFunction() {  
    var msj =$("#divmsj").html();
    var lbl=$('#lbl_msj').val();
    var rol =$("#rol").val();
    if (msj=="")    
    {
        $("#divmsj").css("display", "none"); 
    }else 
    {
        if(lbl == "Anexo creado correctamente"){
                $("#divmsj").addClass("alert-success");
                $("#divmsj").css("display", "block"); 
             }
        //if(msj == "Anexo creado correctamente")
        else{
                $("#divmsj").addClass("alert-danger");
                $("#divmsj").css("display", "block"); 
             }
             $("#lbl_medidaMagna").text(msj); 
    }
});

// $(function HtmlFecha(){
//   //  $('#fecha').change(event => {
//     var estacion =$('select[name=estacion]').val();
//     var fecha = e.target.value;

//     $.get('{{ url("/") }}/medidas',{estacion: estacion,fecha: fecha},function(data){
//             $.each(data,function(fetch, miobj){      
//             $("#lbl_medidaMagna").text(miobj.magna+ ' litros'); 
//             $("#lbl_medidaPremium").text(miobj.premium+ ' litros');    
//             $("#lbl_medidaDiesel").text(miobj.diesel+ ' litros');      
//             });
//         });
//     //}); 
// });


// function handler(e){
//     var estacion =$('select[name=estacion]').val();
//     var fecha = e.target.value;

//   $.get('{{ url("/") }}/medidas',{estacion: estacion,fecha: fecha},function(data){
//             $.each(data,function(fetch, miobj){      
//              $("#lbl_medidaMagna").text(miobj.magna+ ' litros'); 
//              $("#lbl_medidaPremium").text(miobj.premium+ ' litros');    
//              $("#lbl_medidaDiesel").text(miobj.diesel+ ' litros');      
//             });
//         });

//   alert(e.target.value + " - "  + fecha + " - "  + estacion);
// }

// $( function() {
   
//    $("#fecha").datepicker({
//      onSelect: function(dateText) {
//        display("Selected date: " + dateText + ", Current Selected Value= " + this.value);
//        $(this).change();
//      }
//    }).on("change", function() {
//      display("Change event");
//    });
 
//    function display(msg) {
//      //$("<p>").html(msg).appendTo(document.body);
//      alert("hola" );
//    }
//    });

$(function(){

$('#txt_compraMagna,#txt_compraPremium,#txt_compraDiesel,#txt_ventaMagna,#txt_ventaPremium,#txt_ventaDiesel').keypress(function(e) {
if(isNaN(this.value + String.fromCharCode(e.charCode))) 
    return false;
})
.on("cut copy paste",function(e){
    e.preventDefault();
});

});
</script>
@endsection 