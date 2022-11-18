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
                        <!--<form method="POST" action="{{url('incidencias')}}" enctype="multipart/form-data">-->
                            @csrf
                            <div class="form-group form-row">
                                <div class="form-group col-md-4">
                                  <label for="tipo_solicitud">Tipo Solicitud</label>
                                  <select id="tipo_solicitud" class="form-control" name="tipo_solicitud">
                                    <option selected>incidencia</option>
                                    <option>requerimiento</option>
                                  </select>
                                </div>
                                
                                <div class="form-group col-md-4">
                                  <label for="prioridad">Prioridad</label>
                                  <!--<select id="prioridad" class="form-control" name="prioridad">
                                    <option selected>alta</option>
                                    <option>media</option>
                                    <option>baja</option>
                                  </select>-->
                                  <input type="text" class="form-control" id="prioridad" name="prioridad" required readonly>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="folio">Folio</label>
                                    <input type="text" class="form-control" id="folio" name="folio" required readonly>
                                    @if ($errors->has('folio'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('folio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group form-row">
                                
                                
                                <div class="form-group col-md-4">
                                  <label for="estacion">Estacion</label>
                                  <select id="estacion" name="estacion" class="form-control" required>
                                        <option value="" selected>Selecciona Estacion...</option>
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->estacion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--<div class="form-group col-md-4">
                                    <label for="posicion">Posicion Equipo</label>
                                    <input type="text" class="form-control" id="posicion" name="posicion" required readonly>
                                </div>-->
                                
                                <div class="form-group col-md-4">
                                  <label for="id_area_estacion">Area Estacion</label>
                                  <select id="id_area_estacion" name="id_area_estacion" class="form-control" required>
                                      
                                  </select>
                                </div>
                                
                                <div class="form-group col-md-4">
                                  <label for="id_equipo">Equipo/SubArea</label>
                                  <select id="id_equipo" name="id_equipo" class="form-control" required>
                                      
                                  </select>
                                                                    
                                </div>
                                
                            </div>
                            
                            <div class="form-group form-row">
                                <div class="form-group col-md-4">
                                    <label for="posiciones">Posicion</label>
                                    <select id="posiciones" name="posiciones" class="form-control" disabled>
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="refacciones">Refaccion</label>
                                    <select id="refacciones" name="refacciones" class="form-control" required>
                                    </select>
                                </div>  
                                
                                <div class="form-group col-md-4">
                                    <label for="producto">Producto</label>
                                    <select id="producto" name="producto" class="form-control" disabled>
                                    </select>
                                </div>                                                                                       
                                
                            </div>
                            
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-4">
                                    <label for="asunto">Asunto</label>
                                    <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto..." required maxlength="50">
                                   @if ($errors->has('asunto'))
                                   <span class="invalid-feedback d-block"  role="alert">
                                        <strong>{{ $errors->first('asunto') }}</strong>
                                   </span>
                                   @endif
                                    
                                </div>  
                                <div class="form-group col-md-4">
                                    <label for="nserie">Selecciona Num. Serie (opcional)</label>
                                    <select class="form-control es-input" id="nserie" name="nserie">
                                    <option value="" selected>Selecciona</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tserie">Número de Serie (opcional)</label>
                                    <input type="text" class="form-control" id="tserie" name="tserie" maxlength="50">
                                   @if ($errors->has('asunto'))
                                   <span class="invalid-feedback d-block"  role="alert">
                                        <strong>{{ $errors->first('asunto') }}</strong>
                                   </span>
                                   @endif
                                    
                                </div> 
                            
                            </div>
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-12">
                                    <label for="descripcion">Descripcion</label>
                                    <textarea style="overflow:auto;resize:none" class="form-control"  id="descripcion" name="descripcion" required maxlength="255"></textarea>
                                    @if ($errors->has('descripcion'))
                                   <span class="invalid-feedback d-block"  role="alert">
                                        <strong>{{ $errors->first('descripcion') }}</strong>
                                   </span>
                                   @endif
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
                                    <a href="#" data-toggle="modal" data-target="#refaccionesmodal" >
                                        <button id="buscarRefacciones" class="btn btn-success"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>
                                    </a>
                                    
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Crear Incidencia</button>                                    
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
        var est = $('select[name=estacion]').val();
        //alert(est);
        //console.log(est);

        // //*HOTEL
        // if(est=="H. INDIGO"){
            
        //     $('#id_area_estacion').select;
        //     $('#id_equipo').empty();
        //     $('#id_area_atencion').empty();
                
       // }//FIN-HOTEL*

            $.get('{{ url("/") }}/areas',{estacion: est},function(data){
                $('#id_area_estacion').empty();
                $('#id_equipo').empty();
                $('#id_area_atencion').empty();
                
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
        
        //aqui debo obtener los productos estacion
        $.get('{{ url("/") }}/productos_estaciones',{estacion: est},function(data){
            //console.log(data);
            $('#producto').empty();
            //$('#id_equipo').empty();
            //$('#id_area_atencion').empty();
            
            $('#producto').append("<option value=''>Selecciona Producto</option>");
            $.each(data,function(fetch, miobj){
                $('#producto').append('<option value="' + miobj.producto + '">' + miobj.producto + '</option>');                
            });
        });
        
    });

});  
    
$(document).ready(function(){

    
    //Evento change de combo tipo solicitud
    $('#tipo_solicitud').change(event => {
        //aqui obtengo el folio de la incidencia
        var est = $('select[name=estacion]').val();
        var tipo_solicitud = $('select[name=tipo_solicitud]').val();
        //console.log(tipo_solicitud);
        $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
            //console.log(data);            
            //$.each(data,function(fetch, miobj){
            $("#folio").val(data);               
            //});
        });
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
                $('#id_equipo').append('<option value="' + miobj.id + '">' + miobj.id + "-" + miobj.descripcion + '</option>');                
            });
        });
        
    });
    
    //Evento change de combo equipos
    $('#id_equipo').change(event => {
        $('#refacciones').empty();
        $('#posiciones').prop('disabled', true);
        //var id_area_atencion = $('select[name=id_equipo]').val();
        //console.log(id_area_atencion);
        //$.get('{{ url("/") }}/areas_atencion',{id: id_area_atencion},function(data){
            //$('#id_area_atencion').empty();
            //$('#refacciones').empty();
            //$('#id_area_atencion').append("<option value=''>selecciona Area Atencion</option>");
            //$.each(data,function(fetch, miobj){
                //$('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
            //});
        //});
        
        
        var posicion;
        var id_equipo = $('select[name=id_equipo] option:selected').text();
        id_equipo = id_equipo.split("-");
        id_equipo = id_equipo[0];
        //console.log(id_equipo);
        //obtengo posicion y prioridad del equipo para mostrarlo en la vista
        $.get('{{ url("/") }}/equipo_detalle',{id_equipo: id_equipo},function(data){
            //console.log(data);
            $.each(data,function(fetch, miobj){
                //$("#prioridad").val(miobj.prioridad);
                posicion = miobj.posicion;
                //console.log(posicion);
                //$("#posicion").val(miobj.posicion);
                
            });
            
            $('#posiciones').empty();
            //si el equipo tiene 1 en la columna posicion quiere decir que se debe activar el combo posiciones
            if(posicion==1){
                $('#posiciones').prop('disabled', false);
                var estacion = $('select[name=estacion]').val();
                var id_equipo = $('select[name=id_equipo]').val();
                //console.log("posicion true");
                //console.log(estacion);
                //console.log(id_equipo);
                //$('#posiciones').empty();
                $.get('{{ url("/") }}/posiciones',{id_equipo: id_equipo,estacion: estacion},function(data){
                    //$('#posiciones').empty();
                    $('#posiciones').append("<option value=''>Selecciona Posicion/Lado</option>");
                    $.each(data,function(fetch, miobj){
                        $('#posiciones').append('<option value="' + miobj.posicion + '">' + miobj.posicion + '</option>');                
                    });
                });
            }else{
                //busco refacciones segun el equipo
                var id_equipo = $('select[name=id_equipo]').val();
                
                var estacion = $('select[name=estacion]').val();
                //var posicion = $('select[name=posiciones]').val();
                

				//Si la estacion es "CORPORATIVO" CAMBIO 03-12-2020
                 if(estacion=="CORPORATIVO"){
                        $('#refacciones').empty();
		                $('#refacciones').append("<option value=''>Selecciona Refaccion</option>");
                     $.get('{{ url("/") }}/catalogo_refacciones',{estacion: estacion},function(data){
		                $.each(data,function(fetch, miobj){
                            $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
		                });
                     });
                 }else{
		                $.get('{{ url("/") }}/refaccionesSinPosicion',{id_equipo: id_equipo,estacion: estacion},function(data){
		                    $('#refacciones').empty();
		                    $('#refacciones').append("<option value=''>Selecciona Refaccion</option>");
		                    $.each(data,function(fetch, miobj){
		                        $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
		                    });
		                });
	            } //FIN-CORPORATIVO
            }
                        
        
        });     
        
    });
    
    //Evento change de combo refacciones
    $('#refacciones').change(event => {
        $('#producto').prop('disabled', true);
        var id_equipo = $('select[name=id_equipo] option:selected').text();
        id_equipo = id_equipo.split("-");
        id_equipo = id_equipo[0];
        var estacion = $('select[name=estacion]').val();
        var posicion = $('select[name=posiciones]').val();
        var id_refaccion = $('select[name=refacciones]').val();
        var id_area_atencion;
        var producto;

        //numero_serie
        var descripcion_refaccion = $('select[name=refacciones] option:selected').text();
        //$("#tserie").val(descripcion_refaccion);
        //var_dump(descripcion_refaccion);

        //Si la estacion es "CORPORATIVO"
        var ruta_nombre='refacciones_detalle';
        
        if(estacion=="CORPORATIVO"){
            //id_equipo=1;
            ruta_nombre='refacciones_detalle_Corp';
        }
        $.get('{{ url("/") }}/'+ruta_nombre,{id_equipo: id_equipo,estacion: estacion,posicion: posicion,id_refaccion:id_refaccion},function(data){
            $.each(data,function(fetch, miobj){
                id_area_atencion = miobj.id_area_atencion;  
                $("#prioridad").val(miobj.prioridad);
                producto = miobj.producto;
            });
            
            //activo o desactivo producto
            if (producto == 1){
                $('#producto').prop('disabled', false);
            }
            
            //var id_area_atencion = $('select[name=id_equipo]').val();
            //console.log(id_area_atencion);
            $.get('{{ url("/") }}/areas_atencion',{id: id_area_atencion},function(data){
                $('#id_area_atencion').empty();
                //$('#refacciones').empty();
                //$('#id_area_atencion').append("<option value=''>selecciona Area Atencion</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });

            
            $('#nserie').empty();
            
            //obtengo las series del inventario dependiendo la refaccion seleccionada.
            $.get('{{ url("/") }}/numero_serie',{descripcion_refaccion: descripcion_refaccion,estacion: estacion},function(data){

                $('#nserie').append("<option value=''>Selecciona</option>");
                $.each(data,function(fetch, miobj){
                        $('#nserie').append('<option value="' + miobj.id_inventario + '">' + miobj.serie + '</option>');                
                    });
            });
        
        });
        
    });
    
    //Evento change de combo posiciones    
    $('#posiciones').change(event => {
        var id_equipo = $('select[name=id_equipo] option:selected').text();
        id_equipo = id_equipo.split("-");
        id_equipo = id_equipo[0];
        var estacion = $('select[name=estacion]').val();
        var posicion = $('select[name=posiciones]').val();
        
        //console.log(id_equipo);
        //console.log(estacion);
        //console.log(posicion);
        $.get('{{ url("/") }}/refacciones',{id_equipo: id_equipo,estacion: estacion,posicion: posicion},function(data){
            $('#refacciones').empty();
            $('#refacciones').append("<option value=''>Selecciona Refaccion</option>");
            $.each(data,function(fetch, miobj){
                $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
            });
        });
    });

    $('#nserie').change(event => {
        var selected_serie=$('select[name=nserie] option:selected').text();
        $("#tserie").val(selected_serie);
    }); 

    //select editable
    $('#nserie').editableSelect();
    $('select').editableSelect();

});  
    
       




</script>
@endsection






