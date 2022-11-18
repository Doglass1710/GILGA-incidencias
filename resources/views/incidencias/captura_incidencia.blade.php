@extends('layouts.app')

@section('content')

    <div class="container">  
        @include('incidencias.refaccionesmodal')
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Capturar Incidencia</div>   
                    
                    <div class="card-body">
                        
                        {!!Form::open(array('action'=>'IncidenciasController@store','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            <div class="form-group form-row">
                                <div class="col-md-4">
                                    <label>Tipo Solicitud</label>
                                    <select id="tipo_solicitud" class="form-control" name="tipo_solicitud">
                                    @if(\Auth::user()->role=="indigo")
                                        <option value="incidencia">incidencia</option>
                                    @else
                                        <option value="">Selecciona</option>
                                        <option value="incidencia">incidencia</option>
                                        <option value="requerimiento" selected>requerimiento</option>
                                    @endif
                                    </select>
                                </div>                                
                                <div class="col-md-4">
                                    <label for="estacion">Estacion</label>
                                    <select id="estacion" name="estacion" class="form-control" required>
                                    @if(\Auth::user()->role=="indigo")
                                        <option value="H. INDIGO">H. INDIGO</option>
                                    @else
                                        <option value="" selected>Selecciona Estacion...</option>
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                        @endforeach
                                    @endif
                                    </select>
                                </div> 
                                <div class="col-md-2">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" required readonly>
                                    @if ($errors->has('folio'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('folio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <label for="prioridad">Prioridad</label>
                                    <input type="text" class="form-control" id="prioridad" name="prioridad" required readonly>
                                </div>
                            </div>
                            
                            <div id="div_requerimiento">
                                <div class="form-group form-row">
                                    <div class="col-md-4">
                                        <label for="id_area_estacion">Area Estacion</label>
                                        <select id="id_area_estacion" name="id_area_estacion" class="form-control">                                            
                                        </select>
                                    </div>                                    
                                    <div class="col-md-4">
                                        <label for="id_equipo">Equipo/SubArea</label>                                        
                                        <select id="id_equipo" name="id_equipo" class="form-control{{ $errors->has('id_equipo') ? ' is-invalid' : '' }}">                                            
                                        </select>     
                                        @if ($errors->has('id_equipo'))
                                            <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('id_equipo') }}</strong>
                                            </span>
                                        @endif                                                                   
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Catálogo de Refacciones</label>
                                        <select id="id_catalogo" name="id_catalogo" class="form-control">                                            
                                        </select>                                        
                                    </div>
                                </div>
                                
                                <div class="form-group form-row">
                                    <div class="form-group col-md-4">
                                        <label for="refacciones">Refaccion</label>
                                        <select id="refacciones" name="refacciones" class="form-control">
                                        </select>
                                    </div>  
                                    <div class="form-group col-md-4">
                                        <label for="cant">Cantidad</label>
                                        <select id="cant" name="cant" class="form-control">
                                            <option value="1 Pieza">1 Pieza</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="posiciones">Posicion</label>
                                        <select id="posiciones" name="posiciones" class="form-control">
                                        </select>
                                    </div>     
                                </div>
                            </div>
                            <div id="div_incidencia">
                                <div class="form-group form-row">                                
                                    <div class="col-md-12">
                                        <label for="asunto">Asunto</label>
                                        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto..." maxlength="50">
                                        @if ($errors->has('asunto'))
                                            <span class="invalid-feedback d-block"  role="alert">
                                                <strong>{{ $errors->first('asunto') }}</strong>
                                            </span>
                                        @endif                                    
                                    </div>  
                                    <!-- <div class="form-group col-md-4">
                                        <label for="nserie">Selecciona Num. Serie (opcional)</label>
                                        <select class="form-control es-input" id="nserie" name="nserie">
                                        <option value="" selected>Selecciona</option>
                                        </select>
                                        <label for="tserie">Número de Serie (opcional)</label>
                                        <input type="text" class="form-control" id="tserie" name="tserie" maxlength="50">                                    
                                    </div>  -->                            
                                </div>
                                <div class="form-group form-row">                                
                                    <div class="col-md-12">
                                        <label for="descripcion">Descripcion</label>
                                        <textarea style="overflow:auto;resize:none" class="form-control"  id="descripcion" name="descripcion" maxlength="255"></textarea>
                                        @if ($errors->has('descripcion'))
                                    <span class="invalid-feedback d-block"  role="alert">
                                            <strong>{{ $errors->first('descripcion') }}</strong>
                                    </span>
                                    @endif
                                    </div>       
                                </div>
                            </div>
                            
                            <div class="form-group form-row">
                                <div class="form-group col-md-4">
                                    <label for="foto_ruta">Selecciona foto</label>
                                    <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_ruta" name="foto_ruta">
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus_incidencia">Estatus</label>
                                    <input type="text" class="form-control" id="estatus_incidencia" name="estatus_incidencia" readonly>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="id_area_atencion">Area Atencion</label>
                                    <select id="id_area_atencion" name="id_area_atencion" class="form-control" required>
                                    </select>
                                    
                                </div>
                                
                            </div>                           
                            
                            <div class="form-group form-row">
                                <div class=" ml-auto">
                                @if(\Auth::user()->role<>"indigo")
                                    <a href="#" data-toggle="modal" data-target="#refaccionesmodal" >
                                        <button id="buscarRefacciones" class="btn btn-success"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>
                                    </a>
                                @endif    
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn_Guardar" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Crear Incidencia</button>                                    
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
$(document).ready(function(){
          
    $('#refacciones_table').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "lengthMenu": "Mostrar _MENU_ filas",
            "zeroRecords": "No hay coincidencias",
            "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ total de registros)",
            "sSearch":         "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
        }
        });   
   
    $("#estatus_incidencia").val("ABIERTA");
    
    //Evento change de combo estaciones
    $('#estacion').change(event => {
        $('#posiciones').empty();
        $('#refacciones').empty();
        $('#id_area_estacion').empty();
        $('#id_equipo').empty();

        var est = $('select[name=estacion]').val();

        /*HOTEL
        if(est=="H. INDIGO"){
            
            // $('#id_area_estacion').append("<option value='1'>HOTEL</option>");
            // $('#id_equipo').append("<option value='1'>ADMINISTRATIVO</option>");
            // $('#refacciones').append("<option value='1'>SOFTWARE Y EQUIPO</option>");
            $('#id_area_atencion').empty();
            $('#id_area_atencion').append("<option value='1'>SISTEMAS</option>");
            //tipo_solicitud
            $('#tipo_solicitud').empty();
            $('#tipo_solicitud').append("<option value='incidencia'>incidencia</option>");
            
            $("#prioridad").val("alta");

            //aqui obtengo el folio de la incidencia        
            //var tipo_solicitud = $('select[name=tipo_solicitud]').val();
            var tipo_solicitud = "incidencia";
            $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
                $("#folio").val(data);   
            });
        }else
        {*/
            $.get('{{ url("/") }}/catalogo_refaccion',{estacion: est},function(data){
                $('#id_catalogo').empty();
                
                $('#id_catalogo').append("<option value=''>Selecciona</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_catalogo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });
            
            $.get('{{ url("/") }}/areas',{estacion: est},function(data){
                $('#id_area_estacion').empty();
                $('#id_equipo').empty();
                
                $('#id_area_estacion').append("<option value=''>Selecciona Area de estacion</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_area_estacion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });


            //aqui obtengo el folio de la incidencia        
            var tipo_solicitud = $('select[name=tipo_solicitud]').val();
            $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
                $("#folio").val(data);   
            });
            

        //}//FIN-HOTEL  
        
    });

});  
    
$(document).ready(function()
{    
    //*HOTEL
    var est = $('select[name=estacion]').val();
    if(est=="H. INDIGO")
    {
        $("#div_requerimiento").css("display", "none");
        var tipo_solicitud = "incidencia";
            $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
                $("#folio").val(data);   
            });
    }else{
        $("#div_incidencia").css("display", "none");
    }    
    
    // $.get('{{ url("/") }}/areas_atencion',{id: ''},function(data){
    //         $('#id_area_atencion').empty();
    //             $('#id_area_atencion').append("<option value=''>Selecciona</option>");  
    //         $.each(data,function(fetch, miobj){
    //             $('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                               
    //         });
    //     });

    //Evento change de combo tipo solicitud
    $('#tipo_solicitud').change(event => {
        //aqui obtengo el folio de la incidencia
        var est = $('select[name=estacion]').val();
        var tipo_solicitud = $('select[name=tipo_solicitud]').val();
        //console.log(tipo_solicitud);
        $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
            $("#folio").val(data);     
        });
        
        if(tipo_solicitud=="requerimiento"){
            $("#div_incidencia").css('display','none');
            $("#div_requerimiento").css("display", "block");
            $('#id_area_atencion').empty();
        }else{
            $("#div_requerimiento").css("display", "none");
            $("#div_incidencia").css('display','block');

            $.get('{{ url("/") }}/areas_atencion',{id: ''},function(data){
                $('#id_area_atencion').empty();
                    $('#id_area_atencion').append("<option value=''>Selecciona</option>");  
                $.each(data,function(fetch, miobj){
                    $('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                               
                });
            });
        }

    });
    
    //Evento change de combo areas estacion
    $('#id_area_estacion').change(event => {
        //Necesito la estacion seleccionada
        var est = $('select[name=estacion]').val();
        
        //Necesito el id_area_estacion
        var id_area_estacion = $('select[name=id_area_estacion]').val();
        
        $.get('{{ url("/") }}/equipos',{estacion: est, id_area_estacion: id_area_estacion},function(data){
            //console.log(data);
            $('#id_equipo').empty();
            $('#id_area_atencion').empty();
            $('#id_equipo').append("<option value=''>Selecciona Equipo/SubArea</option>");
            $.each(data,function(fetch, miobj){
                $('#id_equipo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
            });
        });
        
    });
    
    //Evento change de combo catalogo
    $('#id_catalogo').change(event => {
        var id="";
        var estacion =$('select[name=estacion]').val();
        // var aux_asunto = $('select[name=id_catalogo]').find('option:selected').text();
        // $('#aux_asunto').val(aux_asunto);

        if(estacion=="CORPORATIVO"){
            id=98;      //ID_CATALOGO PARA REFACCIONES DE CORPORATIVO
        }else{
            id = $('select[name=id_catalogo]').val();
        }

        $.get('{{ url("/") }}/refacciones',{id: id},function(data){
            $('#refacciones').empty();
            $('#refacciones').append("<option value=''>Selecciona Refacción</option>");
            $.each(data,function(fetch, miobj){
                $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>'); 
                $("#prioridad").val(miobj.prioridad);       
                //$('#id_area_atencion').append('<option value="' + miobj.id_area_atencion + '">'+''+ '</option>');
            });
        });

        if(id<=3)   //solo para refacc. dispensarios, disp aire agua y calcomanias
        {            
            $.get('{{ url("/") }}/posiciones',{estacion: estacion, id: id},function(data){
                $('#posiciones').empty();
                $('#posiciones').append("<option value=''>Selecciona Posicion/Lado</option>");
                $.each(data,function(fetch, miobj){
                $('#posiciones').append('<option value="' + miobj.posicion + 
                '">dispensario '+ miobj.id_equipo +', posición '+ miobj.posicion + '</option>');                
                });
            });
        }else{
            $('#posiciones').empty();
        }        
    });
    
    //Evento change de combo refacciones
    $('#refacciones').change(event => 
    {
        var id = $('select[name=id_catalogo]').val();
        var estacion =$('select[name=estacion]').val();
        var id_area_atencion="";
        var id_refaccion = $('select[name=refacciones]').val();
        //$('#aux_asunto').val("Oficina/Instalaciones");

        $.get('{{ url("/") }}/refacciones_detalle',{id: id_refaccion},function(data){            
            $.each(data,function(fetch, miobj){
                id_area_atencion = miobj.id_area_atencion;  
                /*$('#aux_area').val(id_area_atencion);
                $("#id_area_atencion option[value='"+id_area_atencion+"']").attr("selected", true);
                */
                    $.get('{{ url("/") }}/areas_atencion',{id: id_area_atencion},function(data){
                    $('#id_area_atencion').empty();
                    $.each(data,function(fetch, miobj){
                        $('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                               
                        });
                    }); 
                
            });
        });

        if(id==4)
        {
            $('#cant').empty();
            $('#cant').append("<option value=''>Selecciona</option>");
            $('#cant').append("<option value='1 LTS'>1 LTS</option>");
            $('#cant').append("<option value='2 LTS'>2 LTS</option>");
            $('#cant').append("<option value='4 LTS'>4 LTS</option>");
            $('#cant').append("<option value='19 LTS'>19 LTS</option>");

            if(id_refaccion==119)
            {
                $('#cant').empty();
                $('#cant').append("<option value=''>Selecciona</option>");
                $('#cant').append("<option value='1 Pulg'>1''</option>");
                $('#cant').append("<option value='2 Pulg'>2''</option>");
                $('#cant').append("<option value='3 Pulg'>3''</option>");
                $('#cant').append("<option value='4 Pulg'>4''</option>");
            }
            else if(id_refaccion==120 || id_refaccion==194)
            {   
                $('#cant').empty();
                $('#cant').append("<option value=''>Selecciona</option>");
                $('#cant').append("<option value='1 Chico'>1 Chico</option>");
                $('#cant').append("<option value='1 Grande'>1 Grande</option>");
            }
        }
        else
        {
            $('#cant').empty();
            $('#cant').append("<option value='1 Pieza'>1 Pieza</option>");
        }
        
    });

    $('#btn_Guardar').click(function()
    {
        var tipo_solicitud = $('select[name=tipo_solicitud]').val();
        //campos obligatorios
        var area =$('select[name=id_area_estacion]').val();
        var equipo=$('select[name=id_equipo]').val();
        var catalogo="";
        var refaccion="";

        if(tipo_solicitud=="requerimiento")
        {
            if(area=="" || equipo=="")
            {
                alert("Debes seleccionar el area de atencion y equipo");
                hasError = true;
            }
            
        }else
        {
            hasError = false;
        }
        if(hasError) event.preventDefault();
    });
});  



</script>
@endsection






