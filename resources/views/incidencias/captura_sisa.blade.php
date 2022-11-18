@extends('layouts.app')

@section('content')

    <div class="container">  
    <!-- @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif -->

    <div class="alert alert-success" id="divmsj">
        {{ $message ?? ''}}
    </div>
    
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header" style="text-align:center"><h5>TRANSPORTES SEBASTOPOL, SA DE CV</h5>
                    </div>   
                    
                    <div class="card-body" onload="tanquesload()" >
                        
                        {!!Form::open(array('method'=>'POST','action'=>'IncidenciasController@captura_sisa_guardar','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{url('incidencias')}}" enctype="multipart/form-data">-->
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
                            

                            <!--Pequeños encabezados-->
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">        
                                    <label>Gerente: </label>  
                                    <select id="txt_gerente" name="txt_gerente" class="form-control" required>
                                    @if($role=="admin")
                                        <option value="0">Selecciona..</option>
                                    @endif
                                        <option value="{{$estacion->nombre}}">{{$estacion->nombre}}</option>
                                    </select>      
                                    <!-- <input type="text" class="form-control" id="txt_gerente" name="txt_gerente" required /> -->
                                </div>  
                                <div class="form-group col-md-6">
                                    <label>Operador: </label>
                                    <input type="text" class="form-control" id="txt_operador" name="txt_operador" required />
                                </div>                                 
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" min="2021-01-01" required/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Hora</label>
                                    <input type="time" class="form-control" id="hora" name="hora" required/>
                                </div>
                                <div class="form-group col-md-3">
                                <label>Tipo Combustible</label>
                                    <select id="producto" name="producto" class="form-control" required>
                                        <option value="">Selecciona...</option>
                                        <option value="Magna">Magna</option>
                                        <option value="Premium">Premium</option>
                                        <option value="Diesel">Diesel</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Volumen: </label>
                                    <input type="text" class="form-control" id="txt_volumen" name="txt_volumen" required />
                                </div>
                            </div>                            

                            <div class="form-row">
                                <div class="form-group col-md-4">        
                                    <label>Placas Unidad: </label>        
                                    <input type="text" class="form-control" id="txt_placas" name="txt_placas" required />
                                </div>  
                                <div class="form-group col-md-4">
                                    <label>Remision: </label>
                                    <input type="text" class="form-control" id="txt_remision" name="txt_remision" required />
                                </div>    
                                <div class="form-group col-md-4">
                                    <label>Factura: </label>
                                    <input type="text" class="form-control" id="txt_factura" name="txt_factura" required />
                                </div>                                 
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">        
                                    <label>Volumen Inicial: </label>        
                                    <input type="text" class="form-control" id="txt_vinicial" name="txt_vinicial" required />
                                </div>  
                                <div class="form-group col-md-4">
                                    <label>Volumen Final: </label>
                                    <input type="text" class="form-control" id="txt_vfinal" name="txt_vfinal" required />
                                </div>    
                                <div class="form-group col-md-4">
                                    <label>Aumento Bruto: </label>
                                    <input type="text" class="form-control" id="txt_aumento" name="txt_aumento" required />
                                </div>                                 
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">        
                                    <label>Venta en Descarga: </label>        
                                    <input type="text" class="form-control" id="txt_venta" name="txt_venta" required />
                                </div>  
                                <div class="form-group col-md-6">
                                    <label>Cubetas Fin Descarga (19 lts.): </label>
                                    <input type="text" class="form-control" id="txt_cubetas" name="txt_cubetas" placeholder="Total en litros" required />
                                </div>                                  
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Observaciones: </label>
                                    <textarea class="form-control" id="txt_observ" name="txt_observ" style="overflow:auto;resize:none" maxlength="255"></textarea>
                                </div>                                 
                            </div>

                            <div class="dropdown-divider"></div><br/>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Foto sisa:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_sisa" name="foto_sisa" required/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Foto sello domo:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_domo" name="foto_domo" required/>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Foto sello de caja de válvulas:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_valvula" name="foto_valvula" required/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Foto remisión:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_remision" name="foto_remision" required/>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Foto tanque vacío:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_tanque" name="foto_tanque" required/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Foto tira de descarga:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_tira" name="foto_tira" required/>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Foto cubetas:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_cubetas" name="foto_cubetas" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Foto de sistema (venta durante la descarga):</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_venta" name="foto_venta" required/>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Foto ticket de relleno:</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_relleno" name="foto_relleno" />
                                </div>
                                <div class="form-group col-md-6">
                                    
                                </div>
                            </div>
 
                            <br/>
                            <div class="form-group form-row">

                                <div class=" ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                </div>
                            </div>  
                            <br/>
                            
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

    $('#txt_volumen,#txt_remision,#txt_factura,#txt_venta,#txt_cubetas').keypress(function(e) {
    if(isNaN(this.value + String.fromCharCode(e.charCode))) 
        return false;
    })
    .on("cut copy paste",function(e){
        e.preventDefault();
    });

});

$(function(){

$('#txt_vinicial,#txt_vfinal,#txt_aumento').keypress(function(e) {
    if(isNaN(this.value + String.fromCharCode(e.charCode))) 
        return false;
    })
    .on("cut copy paste",function(e){
        e.preventDefault();
    });

});

$(function(){
var d = new Date();
//document.write(d.getDate());
$('#fecha').append(d.getDate());
});

$(function tanquesload() {
    var msj=$("#divmsj").html();

    if(msj==""){
        $("#divmsj").css("display", "none");
    }else{
        $("#divmsj").css("display", "block");
    }
    
}); 

$(function(){
    $('#estacion').change(event => {
        var estacion =$('select[name=estacion]').val();
           
        $.get('{{ url("/") }}/companias',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#lbl_razon_Social").text(miobj.razon_social);                  
            });
        });

        $.get('{{ url("/") }}/gerentes',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#txt_gerente").empty();  
            $("#txt_gerente").append('<option value="' + miobj.nombre + '">' + miobj.nombre + '</option>');                  
            });
        });

    });

});

</script>
@endsection






