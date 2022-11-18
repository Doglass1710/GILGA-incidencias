@extends('layouts.app')

@section('content')

    <div class="container">  

    <div class="alert" id="divmsj">
        {{ $message ?? ''}}
    </div>
    
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header" style="text-align:center"><h5>BITACORA DE DISPENSARIOS</h5>
                    </div>   
                    
                    <div class="card-body" onload="load()" >
                        
                        {!!Form::open(array('method'=>'POST','action'=>'IncidenciasController@dispensarios_bit','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            <div class="form-row">                            
                                <div class="form-group col-md-4">
                                    <label>Estación: </label>                                    
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        @if($role=="admin")
                                            <option value="" selected>Selecciona Estacion...</option>   
                                        @endif 
                                        @foreach($estaciones as $estacion)                                        
                                            <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>   
                                        @endforeach                                     
                                    </select>
                                    <input type="text" name="aux" id="aux" value="{{ $message }}" hidden/>
                                </div>
                                
                                <div class="form-group col-md-8">
                                    <label>Razón social: </label>
                                    <label class="form-control" id="lbl_razon_Social">
                                        @if($role<>"admin")                              
                                            {{ $estacion->razon_social }}
                                        @endif 
                                    </label>
                                </div>

                            </div>
                            

                           
                            <div class="form-row">
                                <div class="form-group col-md-8">        
                                    <label>Evento: </label>        
                                    <select id="evento" name="evento" class="form-control" required>
                                        <option value="">Selecciona un evento..</option>
                                        @foreach($eventos as $ev)
                                            <option value="{{ $ev->id }}">{{ $ev->evento }}</option>
                                        @endforeach
                                    </select>
                                </div>    

                                <div class="form-group col-md-4">
                                    <label>Folio del Acuse: </label>
                                    <input type="text" class="form-control" id="folio" name="folio" required style="text-transform:uppercase" maxlength="25"/>
                                </div>                         
                            </div>   
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" min="2021-01-01" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Hora</label>
                                    <input type="time" class="form-control" id="hora" name="hora" required/>
                                </div>                                      
                                <div class="form-group col-md-4">       
                                    <label id="lbl_factor">Factor Ajuste</label>
                                    <input type="text" id="valor" name="valor" class="form-control" required/>
                                    <select id="descripcion" name="descripcion" class="form-control" required style="display:none">
                                        <option value="">Selecciona..</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Dispensario</label>
                                    <select id="dispensario" name="dispensario" class="form-control" required>
                                        <option value="">Selecciona..</option>
                                    </select>
                                </div>          
                                <div class="form-group col-md-4">
                                    <label>Posición: </label>
                                    <select id="posicion" name="posicion" class="form-control" disabled>
                                        <option value="">Selecciona..</option>
                                    </select>
                                </div>           
                                <div class="form-group col-md-4">
                                    <label>Producto: </label>
                                    <select id="producto" name="producto" class="form-control" disabled>
                                        <option value="">Selecciona..</option>
                                    </select>
                                </div>              
                            </div>   
                           
                            <div class="form-group form-row">                                    
                                <div class="form-group col-md-8">            
                                    <label id="lbl_anexar_orden">Anexar Orden de Trabajo: </label>                         
                                    <input type="file" accept=".jpg, .png, .jpeg, .pdf" class="form-control" id="orden_trabajo" name="orden_trabajo" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Folio de Orden de Trabajo: </label>  
                                    <input type="text" class="form-control" id="folio_orden" name="folio_orden" required style="text-transform:uppercase" maxlength="25"/>
                                </div>
                            </div>  
                            <div class="form-group form-row">        
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                </div>
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

$(function(){
var d = new Date();
//document.write(d.getDate());
$('#fecha').append(d.getDate());
});

$(function load() {
    var msj=$("#divmsj").html();
    var alerta=$("#aux").val();

    if(msj==""){
        $("#divmsj").css("display", "none");
    }else{
        if(alerta == "Registro guardado correctamente"){
                $("#divmsj").addClass("alert-success");
                $("#divmsj").css("display", "block"); 
             }
        else{
                $("#divmsj").addClass("alert-danger");
                $("#divmsj").css("display", "block"); 
             }
    }


    var estacion =$('select[name=estacion]').val();
        //$("#dispensario").empty(); 
        //$("#posicion").empty(); 
           
        $.get('{{ url("/") }}/dispensarios',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){    
                $("#dispensario").append('<option value="' + miobj.id_equipo + '">' + miobj.id_equipo + '</option>');        
            });
        });

    
    
}); 

$(function(){
    $('#estacion').change(event => {
        var estacion =$('select[name=estacion]').val();

        $("#dispensario").empty(); 
        $("#posicion").empty(); 
        $("#folio_orden").val(""); 
           
        $.get('{{ url("/") }}/companias',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
                $("#lbl_razon_Social").text(miobj.razon_social);                  
            });
        });
        $.get('{{ url("/") }}/dispensarios',{estacion: estacion},function(data){
                $("#dispensario").append('<option value="">Selecciona..</option>');
            $.each(data,function(fetch, miobj){    
                $("#dispensario").append('<option value="' + miobj.id_equipo + '">' + miobj.id_equipo + '</option>');        
            });
        });

    });

    $('#evento').change(event => {

        var id =$('select[name=evento]').val();
        var estacion =$('select[name=estacion]').val();
        var fecha= $('#fecha').val();        
        $("#divmsj").css("display", "none");

        $("#descripcion").empty();  
        $("#valor").val("");
        $("#posicion").empty();
        $("#producto").empty();

        $.get('{{ url("/") }}/catalogo',{id: id},function(data){
                $("#descripcion").append('<option value="">Selecciona..</option>');
            $.each(data,function(fetch, miobj){
                $("#descripcion").append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                  
            });
        });
         $.get('{{ url("/") }}/eventos',{id: id},function(data){
             $.each(data,function(fetch, miobj){
                 $("#lbl_factor").html(miobj.factor_ajuste); 
             });
         });         
        $.get('{{ url("/") }}/orden',{estacion: estacion, id: id, fecha: fecha},function(data){
                $("#folio_orden").val("");  
            $.each(data,function(fetch, miobj){    
                $("#folio_orden").val(miobj.orden_folio);  
            });
        });

        if(id==1){
            $("#descripcion").prop('disabled', false);
            $("#descripcion").css("display","block");
            $("#valor").css("display","none");
            
        }else{
            $("#valor").css("display","block");
            $("#descripcion").prop('disabled', true);
            $("#descripcion").css("display","none");
        }

        if(id==1 && $("#posicion").prop('disabled', true)){
            $("#posicion").prop('disabled', false); 
            $("#producto").prop('disabled', false);
        }else{
            $("#posicion").prop('disabled', true); 
            $("#producto").prop('disabled', true);
        }

        if (id==2){
            $("#folio").prop('disabled', false);
            $("#folio_orden").prop('disabled', true);
            $("#producto").prop('disabled', false);
            $("#dispensario").prop('disabled', true);
            $("#lbl_anexar_orden").text("Anexar Acuse CRE:");

            $("#producto").empty(); 
            $.get('{{ url("/") }}/productos_estaciones',{estacion: estacion, id: 9},function(data){
                $.each(data,function(fetch, miobj){    
                    $("#producto").append('<option value="' + miobj.producto + '">' + miobj.producto + '</option>');               
                });
            });
             
        }else{
            $("#folio").prop('disabled', true);
            $("#folio_orden").prop('disabled', false);
            $("#dispensario").prop('disabled', false);
            $("#producto").empty();
            $("#lbl_anexar_orden").text("Anexar Orden de Trabajo:");
        }

        if(id==3 || id==4 || id==1){
            $("#valor").prop('disabled', true);
        }else{
            $("#valor").prop('disabled', false); 
        }

        if(id==5){
            $("#valor").val("<?php echo date("d-m-Y");?> / 17:40");
        }

    });

    $("#descripcion").change(event => {

        var id =$('select[name=descripcion]').val();
        if(id==2){
            $("#posicion").prop('disabled', false); 
            $("#producto").prop('disabled', false); 
        }else{
            $("#posicion").empty();
            $("#producto").empty();
            $("#posicion").prop('disabled', true); 
            $("#producto").prop('disabled', true); 
        }

    });

    $('#dispensario').change(event => {
        
        var estacion =$('select[name=estacion]').val();
        var id =$('select[name=dispensario]').val();
        var id_evento =$('select[name=evento]').val();
        var id_descripcion =$('select[name=descripcion]').val()

        if(id_evento=="1" && id_descripcion=="2")
        {
            $("#posicion").empty(); 
            $.get('{{ url("/") }}/posiciones',{estacion: estacion, id: id},function(data){
                $.each(data,function(fetch, miobj){    
                    $("#posicion").append('<option value="' + miobj.posicion + '">' + miobj.posicion + '</option>');               
                });
            });

            $("#producto").empty(); 
            $.get('{{ url("/") }}/productos_estaciones',{estacion: estacion, id: id},function(data){
                $.each(data,function(fetch, miobj){    
                    $("#producto").append('<option value="' + miobj.producto + '">' + miobj.producto + '</option>');               
                });
            });
        }        

    });

    $('#fecha').change(event => {
        var id =$('select[name=evento]').val();
        var estacion =$('select[name=estacion]').val();
        var fecha= $('#fecha').val();

        //alert(fecha);

        $.get('{{ url("/") }}/orden',{estacion: estacion, id: id, fecha: fecha},function(data){
                $("#folio_orden").val("");  
            $.each(data,function(fetch, miobj){    
                $("#folio_orden").val(miobj.orden_folio);  
            });
        });
       
    });

    $('#btn').click(function(){
        var id_evento =$('select[name=evento]').val();
        var sFileName = $("#orden_trabajo").val();
        
        if(id_evento != 2 && sFileName == "" && $("#folio_orden").val()==""){
            alert("¡CUIDADO! NO HAS INGRESADO ORDEN DE TRABAJO");
            hasError = true;
        }
        else if(id_evento == 2 && sFileName == "")
        {
            alert("¡CUIDADO! NO HAS INGRESADO EL ACUSE CRE");
            hasError = true;
        }
        else{            
            hasError = false;
        }
        if(hasError) event.preventDefault();
    });

});

</script>
@endsection






