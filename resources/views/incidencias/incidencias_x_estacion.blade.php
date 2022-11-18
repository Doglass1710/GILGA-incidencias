@extends('layouts.app')

@section('content')


<div class="container-fluid">
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <div class="row">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-12">
            <h3>Listado de Incidencias&nbsp;<a href="/"><button class="btn btn-info"><i class="fas fa-home fa-1x"></i>&nbsp;Inicio</button></a></h3>
            
            @include('incidencias.editar_incidencia')
            @include('incidencias.modal_requerimiento')
            @include('incidencias.modal_ver')
            
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table id="myTable"  style="font-size: 13px; padding: 0px;"
                data-order='[[ 6, "desc" ]]' data-page-length='25'
                class="table table-striped table-bordered table-condensed table-hover">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Folio</th>
                        <th><i class="fas fa-gas-pump fa-1x"></i>&nbsp;Estacion</th>
                        <th>Nombre Estacion</th>
                        <th>Zona</th>
                        <th>Fecha Incidencia</th>
                        <th>Ultima Actualizacion</th>
                        <!--<th>id area estacion</th>-->
                        <!--<th>id equipo</th>-->
                        <th>Asunto</th>
                        <!-- <th>descripcion</th> -->
                        <th>Area Atencion</th>
                        <!--<th>foto_ruta</th>-->
                        
                        <!--<th>Tipo Solicitud</th>-->
                        <th>Detalle</th>
                        <th>Ver</th>
                        <th>Editar</th>
                        <th>Detalles</th>
                    </thead>
                    @foreach ($incidencias as $inc)
                    <tr >
                    <!--<tr class="{{ $inc->prioridad == 'alta' ? 'table-danger' : 'table-light' }}">-->   
                        <td>{{ $inc->id }}</td>
                        <td>{{ $users->where('id', $inc->id_usuario)->first()->name }}</td>
                        <td>{{ $inc->folio }}</td>
                        <td>{{ $inc->estacion }}</td>
                        <td>{{ $inc->nombre_corto }}</td>
                        <td>{{ $inc->zona }}</td>
                        <td>{{ $inc->fecha_incidencia }}</td>
                        <td>{{ $inc->fecha_ultima_actualizacion }}</td>
                        @if($inc->tipo_solicitud == 'incidencia')
                            <td>{{ $inc->asunto }}</td>
                        @else
                            <td>{{ $inc->refaccion_descripcion }}</td>
                        @endif
                        <td>{{ $inc->area_atencion_descripcion }}</td>
                        <td class="{{ $inc->estatus == 'SOLVENTADO' ? 'table-danger' : 'table-success disabled' }}">
                            {{ $inc->estatus }}
                        </td>                        
                        @if($inc->tipo_solicitud == 'incidencia')
                            <td>
                                <a href="#" data-toggle="modal" data-target="#modal_ver" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-tipo_solicitud="{{ $inc->tipo_solicitud }}" data-estatus_incidencia="{{ $inc->estatus_incidencia }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-id_area_atencion="{{ $inc->id_area_atencion }}" data-descripcion="{{ $inc->descripcion }}" data-asunto="{{ $inc->asunto }}" data-id_equipo="{{ $inc->id_equipo }}" data-id_area_estacion="{{ $inc->id_area_estacion }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}" data-folio="{{ $inc->folio }}" data-id="{{ $inc->id }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}"><button class="btn btn-info"><i class="fas fa-eye"></i>&nbsp;Ver</button></a>
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#editar_incidencia" data-id="{{$inc->id}}" data-id_usuario="{{$inc->id_usuario}}" data-folio="{{$inc->folio}}" data-estacion="{{$inc->estacion}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-id_area_estacion="{{$inc->id_area_estacion}}" data-id_equipo="{{$inc->id_equipo}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-id_area_atencion="{{$inc->id_area_atencion}}" data-foto_ruta="{{$inc->foto_ruta}}" data-estatus_incidencia="{{$inc->estatus_incidencia}}" data-tipo_solicitud="{{$inc->tipo_solicitud}}" data-prioridad="{{$inc->prioridad}}"><button class="btn btn-warning"><i class="fas fa-edit"></i>&nbsp;Requerim.</button></a>

                            </td>
                        @else
                            <td>
                                <a href="#" data-toggle="modal" data-target="#modal_requerimiento" data-id_requerimiento="{{ $inc->id }}" data-cantidad="{{ $inc->cantidad }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}"  data-folio="{{ $inc->folio }}"><button class="btn btn-info"><i class="fas fa-eye"></i>&nbsp;Ver</button></a>
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#editar_incidencia" data-id="{{$inc->id}}" data-id_usuario="{{$inc->id_usuario}}" data-folio="{{$inc->folio}}" data-estacion="{{$inc->estacion}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-id_area_estacion="{{$inc->id_area_estacion}}" data-id_equipo="{{$inc->id_equipo}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-id_area_atencion="{{$inc->id_area_atencion}}" data-foto_ruta="{{$inc->foto_ruta}}" data-estatus_incidencia="{{$inc->estatus_incidencia}}" data-tipo_solicitud="{{$inc->tipo_solicitud}}" data-prioridad="{{$inc->prioridad}}"><button class="btn btn-warning" disabled><i class="fas fa-edit"></i>&nbsp;Requerim.</button></a>

                            </td>
                        @endif
                        
                        <td><a href="{{URL::action('IncidenciasController@show',$inc->id)}}"><button class="btn btn-danger"><i class="fas fa-list-alt"></i>&nbsp;Detalles</button></a></td>
                    </tr>
                    @endforeach
                </table>

            </div>

            
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
                
        $('#myTable').DataTable({
            
            dom: 'lBfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            
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
        
        //para MODAL editar incidencia, se llena en el evento show
        $('#editar_incidencia').on('show.bs.modal', function(event) {
                     
            
            //asi lleno el modal editar_incidencia
            var button = $(event.relatedTarget)
            
            var id = button.data('id')
            var folio = button.data('folio')
            var estacion = button.data('estacion')
            var fecha_incidencia = button.data('fecha_incidencia')
            var tipo_solicitud = button.data('tipo_solicitud')
            var asunto = button.data('asunto')
            var descripcion = button.data('descripcion')
            var prioridad = button.data('prioridad')
            //var id_area_estacion = button.data('id_area_estacion')
            var estatus_incidencia = button.data('estatus_incidencia')
            var foto_ruta = button.data('foto_ruta')
            
            var modal = $(this)

            modal.find('.modal-title').text('Editar Incidencia: ID-> ' + id)
            modal.find('#id').val(id)
            //modal.find('#user').val(user)
            modal.find('#folio').val(folio)
            modal.find('#estacion').val(estacion)
            modal.find('#fecha').val(fecha_incidencia)
            modal.find('#tipo_solicitud').val(tipo_solicitud)
            modal.find('#asunto').val(asunto)
            modal.find('#descripcion').html(descripcion)
            modal.find('#prioridad').val(prioridad)
            modal.find('#foto_ruta_escondida').val(foto_ruta)
            
            modal.find('#estatus_incidencia').val(estatus_incidencia)
            
            //debo cargar los combos
            $.get('{{ url("/") }}/areas',{estacion: estacion},function(data){
                //console.log(data);
                $('#id_area_estacion').empty();
                $('#id_equipo').empty();
                $('#id_area_atencion').empty();

                $('#id_area_estacion').append("<option value=''>selecciona Area de estacion</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_area_estacion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });
            
            //aqui debo obtener los productos estacion
            $.get('{{ url("/") }}/productos_estaciones',{estacion: estacion},function(data){
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

        $('#modal_requerimiento').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_req = button.data('id_requerimiento')
            var usuario= button.data('user')
            var folio = button.data('folio')
            var estacion = button.data('estacion')
            var fecha_incidencia = button.data('fecha_incidencia')
            var id_area_estacion = button.data('id_area_estacion')
            var foto_ruta = button.data('foto_ruta')
            var prioridad = button.data('prioridad')
            var equipo_descripcion = button.data('equipo_descripcion')
            var refaccion_descripcion = button.data('refaccion_descripcion')
            var area_atencion_descripcion = button.data('area_atencion_descripcion')
            var area_estacion_descripcion = button.data('area_estacion_descripcion')
            var posicion = button.data('posicion')
            var cantidad=button.data('cantidad')

            var modal = $(this)

            modal.find('.modal-title').text('Requerimiento: ' + id_req)
            modal.find('#user').val(usuario)
            modal.find('#folio').val(folio)
            modal.find('#estacion').val(estacion)
            modal.find('#fecha_incidencia').val(fecha_incidencia)
            modal.find('#id_area_estacion').val(id_area_estacion)
            modal.find('#prioridad').val(prioridad)
            modal.find('#equipo_descripcion').val(equipo_descripcion)
            modal.find('#refaccion_descripcion').val(refaccion_descripcion)
            modal.find('#area_atencion_descripcion').val(area_atencion_descripcion)
            modal.find('#area_estacion_descripcion').val(area_estacion_descripcion)
            modal.find('#posicion').val(posicion)
            modal.find('#foto').attr("src", '{{ url("/") }}/incidencia/imagenes/' + foto_ruta)
            modal.find('#cantidad').val(cantidad)
        });  
        
        //para MODAL ver incidencia, se llena en el evento show
        $('#modal_ver').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)

            var id = button.data('id')
            var user = button.data('user')
            var folio = button.data('folio')
            var estacion = button.data('estacion')
            var fecha_incidencia = button.data('fecha_incidencia')
            var id_area_estacion = button.data('id_area_estacion')
            var id_equipo = button.data('id_equipo')
            var asunto = button.data('asunto')
            var descripcion = button.data('descripcion')
            var id_area_atencion = button.data('id_area_atencion')
            var foto_ruta = button.data('foto_ruta')
            var estatus_incidencia = button.data('estatus_incidencia')
            var tipo_solicitud = button.data('tipo_solicitud')
            var prioridad = button.data('prioridad')
            var equipo_descripcion = button.data('equipo_descripcion')
            var refaccion_descripcion = button.data('refaccion_descripcion')
            var area_atencion_descripcion = button.data('area_atencion_descripcion')
            var area_estacion_descripcion = button.data('area_estacion_descripcion')
            var posicion = button.data('posicion')

            var modal = $(this)

            modal.find('.modal-title').text('Incidencia: ID: ' + id)
            modal.find('#user').val(user)
            modal.find('#folio').val(folio)
            modal.find('#estacion').val(estacion)
            modal.find('#fecha_incidencia').val(fecha_incidencia)
            modal.find('#id_area_estacion').val(id_area_estacion)
            modal.find('#id_equipo').val(id_equipo)
            modal.find('#asunto').val(asunto)
            modal.find('#descripcion').html(descripcion)
            modal.find('#id_area_atencion').val(id_area_atencion)
            modal.find('#estatus_incidencia').val(estatus_incidencia)
            modal.find('#tipo_solicitud').val(tipo_solicitud)
            modal.find('#prioridad').val(prioridad)
            modal.find('#equipo_descripcion').val(equipo_descripcion)
            modal.find('#refaccion_descripcion').val(refaccion_descripcion)
            modal.find('#area_atencion_descripcion').val(area_atencion_descripcion)
            modal.find('#area_estacion_descripcion').val(area_estacion_descripcion)
            modal.find('#posicion').val(posicion)
            modal.find('#foto').attr("src", '{{ url("/") }}/incidencia/imagenes/' + foto_ruta)

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '/incidencia/' + id + '/status/change',
                data: {},
                success: function() {},
            });
        });
        
        
        //$("#estatus_incidencia").val("ABIERTA");    
        
        //Evento change de combo areas estacion dentro del MODAL
        $('#id_area_estacion').change(event => {
            
            //Necesito la estacion seleccionada
            
            var estacion = $("#estacion").val();

            //Necesito el id_area_estacion
            var id_area_estacion = $('select[name=id_area_estacion]').val();
            
            //console.log(estacion);
            //console.log(id_area_estacion);

            $.get('{{ url("/") }}/equipos',{estacion: estacion, id_area_estacion: id_area_estacion},function(data){
                //console.log(data);
                $('#id_equipo').empty();
                $('#id_area_atencion').empty();
                $('#id_equipo').append("<option value=''>selecciona Equipo/SubArea</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_equipo').append('<option value="' + miobj.id + '">' + miobj.id + "-" + miobj.descripcion + '</option>');                
                });
            });

        });

        //Evento change de combo equipos dentro del MODAL
        $('#id_equipo').change(event => {
            //var id_area_atencion = $('select[name=id_equipo]').val();
            //console.log(id_area_atencion);
            //$.get('{{ url("/") }}/areas_atencion',{id: id_area_atencion},function(data){
                //$('#id_area_atencion').empty();
                //$('#id_area_atencion').append("<option value=''>selecciona Area Atencion</option>");
                //$.each(data,function(fetch, miobj){
                    //$('#id_area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                //});
            //});
            //Codigo nuevo 12/17/2019
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
                    var estacion = $("#estacion").val();
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

                    var estacion = $("#estacion").val();
                    //var posicion = $('select[name=posiciones]').val();

                    //console.log("refacciones segun el equipo");
                    //console.log(id_equipo);
                    //console.log(estacion);
                    //console.log(posicion);
                    $.get('{{ url("/") }}/refaccionesSinPosicion',{id_equipo: id_equipo,estacion: estacion},function(data){
                        $('#refacciones').empty();
                        $('#refacciones').append("<option value=''>Selecciona Refaccion</option>");
                        $.each(data,function(fetch, miobj){
                            $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                        });
                    });
                }


            });
        });
        
        //Evento change de combo posiciones    
        $('#posiciones').change(event => {
            var id_equipo = $('select[name=id_equipo] option:selected').text();
            id_equipo = id_equipo.split("-");
            id_equipo = id_equipo[0];
            var estacion = $("#estacion").val();
            var posicion = $('select[name=posiciones]').val();

            //console.log(id_equipo);
            //console.log(estacion);
            //console.log(posicion);
            $.get('{{ url("/") }}/refacciones',{id_equipo: id_equipo,estacion: estacion,posicion: posicion},function(data){
                $('#refacciones').empty();
                $('#refacciones').append("<option value=''>Selecciona Refacción</option>");
                $.each(data,function(fetch, miobj){
                    $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });
        });
        
        //Evento change de combo refacciones
        $('#refacciones').change(event => {
            $('#producto').prop('disabled', true);
            var id_equipo = $('select[name=id_equipo] option:selected').text();
            id_equipo = id_equipo.split("-");
            id_equipo = id_equipo[0];
            var estacion = $("#estacion").val();
            var posicion = $('select[name=posiciones]').val();
            var id_refaccion = $('select[name=refacciones]').val();
            var id_area_atencion;
            var producto;
            //var prioridad;
            //onsole.log(id_equipo);
            //console.log(estacion);
            //console.log(posicion);
            //console.log(id_refaccion);
            $.get('{{ url("/") }}/refacciones_detalle',{id_equipo: id_equipo,estacion: estacion,posicion: posicion,id_refaccion:id_refaccion},function(data){
                //$('#refacciones').empty();
                //$('#refacciones').append("<option value=''>selecciona Refaccion</option>");
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

            });



        });
        

    });
</script>
@endsection

