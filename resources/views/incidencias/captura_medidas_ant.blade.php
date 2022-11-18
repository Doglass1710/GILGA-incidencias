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
                    <div class="card-header"><h5>Capturar Medidas Diarias</h5>
                    @include('incidencias.modal_detalles_inc_cerradas')
                    </div>   
                    
                    <div class="card-body" onload="tanquesload()" >
                        
                        {!!Form::open(array('method'=>'POST','action'=>'IncidenciasController@guardar_medidas','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{url('incidencias')}}" enctype="multipart/form-data">-->
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="estacion">Estacion: </label>
                                    <select id="estacion" name="estacion" class="form-control"  onload="myFunction()" required>
                                    <!-- <option value="" selected>Selecciona Estacion...</option> -->
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="estacion">Fecha: </label>
                                    <select name="fecha" id="fecha" class="form-control" required>
                                    <option value="" selected>Selecciona...</option>
                                    @if($fechaFormato=="lunes")
                                        <option value="<?php echo date('Y-m-d',strtotime('-2 day'));?>"><?php echo date("d-m-Y",strtotime('-2 day'));?></option>
                                        <option value="<?php echo date('Y-m-d',strtotime('-1 day'));?>"><?php echo date("d-m-Y",strtotime('-1 day'));?></option>
                                        <option value="<?php echo date('Y-m-d');?>"><?php echo date("d-m-Y");?></option>
                                    @else
                                        @foreach($dias as $d)
                                            <option value="<?php echo date('Y-m-d',strtotime('-'. $d .' day'));?>"><?php echo date("d-m-Y",strtotime('-'. $d .' day'));?></option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="estacion">Descripción: </label>
                                    @foreach($ValidarHorario as $ValidarHorario)
                                    <input type="text" name="HorarioActivo" value="{{$ValidarHorario->HorarioActivo}}" hidden/>
                                    @endforeach
                                    <label class="form-control" id="lbl_capacidad"></label>
                                </div>

                                <div class="form-group col-md-1">
                                @if ($id_usuario_permiso=="42" || $id_usuario_permiso=="53")
                                    <p class="btn btn-default" id="btn_ActivarHorario">
                                        <img src="Horario.PNG">
                                    </p>
                                @endif
                                </div>
                            </div>
                            

                            <!--Pequeños encabezados-->
                            <div class="row">
                                <div class="col-md-3">
                                <label></label>
                                </div>
                                <div class="col-md-3">
                                <label>Medidas Diarias</label>
                                </div>
                                <div class="col-md-3">
                                <label>Capacidad de tanques</label>
                                </div>
                                <div class="col-md-3">
                                <label>Pipa que va a llegar</label>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="Magna" class="btn btn-success" style="width:100%;">
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Magna</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="txt_magna" name="txt_magna" required maxlength="50">
                                </div> 
                                <div class="col-md-3">
                                   <label class="form-control" id="txt_TanqueMagna"></label>
                                </div> 
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="pipa1" name="pipa1" maxlength="50">
                                </div> 
                                <div class="col-md-1">
                                    <p class="btn btn-default" id="ob1" style="margin-bottom:0;">
                                        <i class="fas fa-plus-square fa-1x"></i>
                                    </p>
                                    <figure id="fig1" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 180px;padding: 4px 12px; display:none;">
                                        <figcaption>Agregar Observaciones</figcaption>                                    
                                    </figure>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">

                                    <input type="text" class="form-control" id="txt_magna_T2" name="txt_magna_T2" placeholder="Magna Tanque 2" style="display:none">
                            
                                </div>
                                <div class="form-group col-md-8">                                
                                    <!-- <input type="text" class="form-control" id="observaciones_pipa1" name="observaciones_pipa1" maxlength="255" placeholder="Observaciones :"  style="display:none"> -->
                                    <select id="ob_p1" name="ob_p1" class="form-control" style="display:none">
                                        <option value="0" selected>Selecciona una opción...</option>
                                        @foreach ($catalogo_ob as $ob)
                                        <option value="{{$ob->descripcion}}">{{$ob->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="Premium" class="btn btn-danger" style="width:100%;">
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Premium</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="txt_premium" name="txt_premium" required maxlength="50">
                                </div>           
                                <div class="col-md-3">
                                    <label class="form-control" id="txt_TanquePremium"><label/>
                                </div>     
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="pipa2" name="pipa2" maxlength="50">
                                </div>  
                                <div class="col-md-1">
                                    <p class="btn btn-default" id="ob2" style="margin-bottom:0;">
                                        <i class="fas fa-plus-square fa-1x"></i>
                                    </p>
                                    <figure id="fig2" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 180px;padding: 4px 12px; display:none;">
                                        <figcaption>Agregar Observaciones</figcaption>                                    
                                    </figure>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                </div>
                                <div class="form-group col-md-8">                                
                                <select id="ob_p2" name="ob_p2" class="form-control" style="display:none">
                                        <option value="0" selected>Selecciona una opción...</option>
                                        @foreach ($catalogo_ob as $ob)
                                        <option value="{{$ob->descripcion}}">{{$ob->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-3">        
                                    <label for="Diesel" class="btn btn-primary" style="background-color:black;width:100%;" disabled>
                                    <i class="fas fa-gas-pump fa-1x"></i>&nbsp;Diesel</label>                   
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="text" class="form-control" id="txt_diesel" name="txt_diesel" required maxlength="50">
                                </div>  
                                <div class="form-group col-md-3">
                                    <label class="form-control" id="txt_TanqueDiesel" ></label>
                                </div> 
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" id="pipa3" name="pipa3" maxlength="50">
                                </div> 
                                <div class="col-md-1">
                                    <p class="btn btn-default" id="ob3" style="margin-bottom:0;">
                                        <i class="fas fa-plus-square fa-1x"></i>
                                    </p>
                                    <figure id="fig3" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 180px;padding: 4px 12px; display:none;">
                                        <figcaption>Agregar Observaciones</figcaption>                                    
                                    </figure>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                </div>
                                <div class="form-group col-md-8">                                
                                <select id="ob_p3" name="ob_p3" class="form-control" style="display:none">
                                        <option value="0" selected>Selecciona una opción...</option>
                                        @foreach ($catalogo_ob as $ob)
                                        <option value="{{$ob->descripcion}}">{{$ob->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                         
                            
                            <div class="form-group form-row">

                                <div class=" ml-auto"> 
                                @if ($id_usuario_permiso=="42" || $id_usuario_permiso=="53")
                                    <a href="{{ url('abrirDias') }}">
                                        <div class="btn btn-danger"><i class="fas fa-list-alt"></i>&nbsp;Habilitar días</div>
                                    </a>
                                @endif
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-primary" onclick="myFunction()"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                </div>
                            </div>  
                            
                            <div class="form-group form-row">
                             <!--Tabla-->
                             <div class="table-responsive" id="div_tabla">
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                    <th>Estacion</th>
                                    <th>Magna</th>
                                    <th>Premium</th>
                                    <th>Diesel</th>  
                                    <th>Fecha</th>       
                                </thead>
                                    @foreach ($medidas_tbl as $med)
                                    <tr > 
                                        <td>{{ $med->estacion }}</td>                        
                                        <td>{{ $med->magna  }}</td>
                                        <td>{{ $med->premium}}</td>
                                        <td>{{ $med->diesel }}</td> 
                                        <td>{{ $med->fecha_aplica}}</td>                    
                                    </tr>
                                    @endforeach
                                </table>
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

@section('script')
<script>

$(function(){

    $('#txt_magna,#txt_premium,#txt_diesel,#txt_magna_T2').keypress(function(e) {
    if(isNaN(this.value + String.fromCharCode(e.charCode))) 
        return false;
    })
    .on("cut copy paste",function(e){
        e.preventDefault();
    });

});

$(function(){

$('#pipa1,#pipa2,#pipa3').keypress(function(e) {
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

//si funciona local, lamentablemente no va a funcionar arriba. :( 
$(function myFunction() {  
    if ($("input:text").val()==0)
    {
        alert("Los sentimos, estas intentando capturar Fuera de horario");
        $("#btn").prop('disabled', true);
    }else{        
        $("#divmsj").css("display", "none"); 
    }
});

$(function tanquesload() {
    var estacion =$('select[name=estacion]').val();

    if(estacion=="5420"){
        $("#txt_magna_T2").css("display","block");
    }

    $.get('{{ url("/") }}/tanques',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#lbl_capacidad").text(miobj.descripcion);
            $("#txt_TanqueMagna").text(miobj.magna+ ' litros'); 
            $("#txt_TanquePremium").text(miobj.premium+ ' litros');    
            $("#txt_TanqueDiesel").text(miobj.diesel+ ' litros');                  
            });
        });
}); 

$(function(){
    $('#estacion').change(event => {
        var estacion =$('select[name=estacion]').val();
        if(estacion=="5420"){
        $("#txt_magna_T2").css("display","block");
        }else{
            $("#txt_magna_T2").css("display","none");
        }
   
        $.get('{{ url("/") }}/tanques',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#lbl_capacidad").text(miobj.descripcion);
            $("#txt_TanqueMagna").text(miobj.magna+ ' litros'); 
            $("#txt_TanquePremium").text(miobj.premium+ ' litros');    
            $("#txt_TanqueDiesel").text(miobj.diesel+ ' litros');                  
            });
        });
    });

    $("#btn_ActivarHorario").click(function(){
        var horario=$("input:text").val();

        $.get('{{ url("/") }}/horario',{horario: horario},function(data){            
            $("#divmsj").css("display", "block");

            if(data==1){
                $("#btn").prop('disabled', false);
                $("#divmsj").html("Se activo el horario");
            }else{
                $("#btn").prop('disabled', true);
                $("#divmsj").html("Se cierra el horario de captura");
            }
           
           // alert(data);
        });

    });

    $("#ob1").click(function(){
        if($("#ob_p1").css("display")=="none"){
            $("#ob_p1").css("display","block");
        }else{
            $("#ob_p1").css("display","none");
        }
    });

    $("#ob2").click(function(){
        if($("#ob_p2").css("display")=="none"){
            $("#ob_p2").css("display","block");
        }else{
            $("#ob_p2").css("display","none");
        }
    });
    $("#ob3").click(function(){
        if($("#ob_p3").css("display")=="none"){
            $("#ob_p3").css("display","block");
        }else{
            $("#ob_p3").css("display","none");
        }
    });


    $("#ob1").hover(function(){
        $("#fig1").css("display","block"); 
        }, function(){
            $("#fig1").css("display","none"); 
    });

    $("#ob2").hover(function(){
        $("#fig2").css("display","block"); 
        }, function(){
            $("#fig2").css("display","none"); 
    });

    $("#ob3").hover(function(){
        $("#fig3").css("display","block"); 
        }, function(){
            $("#fig3").css("display","none"); 
    });

    // $('#btn').click(function(){
    //     $("#div_tabla").css("display","block");
    // });


});

</script>
@endsection