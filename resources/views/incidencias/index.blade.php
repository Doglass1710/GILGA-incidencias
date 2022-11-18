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
            <h3>Listado de Incidencias&nbsp;
                <a href="incidencias/create"><button class="btn btn-success"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Incidencia</button></a>&nbsp;
                <a href="/listado_incidencias"><button class="btn btn-info"><i class="fas fa-sync fa-1x"></i>&nbsp;Actualizar</button></a>            
            </h3>
            @include('incidencias.modal_requerimiento')
            @include('incidencias.editar_incidencia')
            @include('incidencias.modal_ver')
            @include('incidencias.modal_comentarios')
            @include('incidencias.modal_inventario')
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
                        <th>Estacion <!-- <i class="fas fa-gas-pump fa-1x"></i>&nbsp; </th><th>Nombre Estacion-->
                        </th>
                        <th>Zona</th>
                        <th>Fecha Incidencia</th>
                        <th>Ultima Actualiz.</th>
                        <!--<th>id area estacion</th>-->
                        <!--<th>id equipo</th>-->
                        <th>Asunto</th>
                        <!-- <th>descripcion</th> -->
                        <th>Area Atencion</th>
                        <!--<th>Imagen</th>-->
                        
                        <!--<th>Tipo Solicitud</th>-->
                        <th>Estatus</th>
                        <th>Detalle</th>
                        <th>Comentar
                            <!-- <p class="btn btn-default" id="ob1" style="margin-bottom:0;color:#fff">
                                <i class="fas fa-comment fa-1x"></i>
                            </p>
                            <figure id="fig1" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 100px;padding: 4px 12px; display:none;">
                                <figcaption>Comentar</figcaption>                                    
                            </figure> -->
                        </th>
                        <th>Ver
                            <!-- <p class="btn btn-default" id="ob2" style="margin-bottom:0;color:#fff">
                                <i class="fas fa-eye fa-1x"></i>
                            </p>
                            <figure id="fig2" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 80px;padding: 4px 12px; display:none;">
                                <figcaption>Ver</figcaption>                                    
                            </figure> -->
                        </th>
                        <th>Agregar Requer.
                            <!-- <p class="btn btn-default" id="ob3" style="margin-bottom:0;color:#fff">
                                <i class="fas fa-edit fa-1x"></i>
                            </p>
                            <figure id="fig3" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 100px;padding: 4px 12px; display:none;">
                                <figcaption>Agregar<br/>Requerimiento</figcaption>                                    
                            </figure> -->
                        </th>
                        <th>Detalles
                            <!-- <p class="btn btn-default" id="ob4" style="margin-bottom:0;color:#fff">
                                <i class="fas fa-list-alt fa-1x"></i>
                            </p>
                            <figure id="fig4" style="position: absolute;background-color: rgba(0, 0, 0, .5); border-radius: 5px; color: #fff;width: 100px;padding: 4px 12px; display:none;">
                                <figcaption>Detalles</figcaption>                                    
                            </figure> -->
                        </th>
                    </thead>
                    @foreach ($incidencias as $inc)
                    <tr > 
                        <td>{{ $inc->id }}</td>                        
                        <td>{{ $users->where('id', $inc->id_usuario)->first()->name  }}</td>
                        <td>{{ $inc->folio }}</td>
                        <td>{{ $inc->estacion }} - {{ $inc->nombre_corto }}</td>
                        <td>{{ $inc->zona }}</td>
                        <td>{{ $inc->fecha_incidencia }}</td>
                        <td>{{ $inc->fecha_ultima_actualizacion }}</td>

                        @if($inc->tipo_solicitud == 'incidencia')
                            <td>{{ $inc->asunto }}</td>
                        @else
                            <td>{{ $inc->refaccion_descripcion }}</td>
                        @endif
                        <td>{{ $inc->area_atencion_descripcion }}</td>
                        
                        <!--<td>{{ $inc->tipo_solicitud }}</td>
                        <td class="{{ $inc->prioridad == 'alta' ? 'table-danger' : ($inc->prioridad == 'media' ? 'table-warning' : 'table-light') }}">{{ $inc->prioridad }}</td>-->
                        
                        <td class="{{ $inc->estatus == 'SOLVENTADO' ? 'table-danger' : 'table-success disabled' }}">
                            {{ $inc->estatus }}
                        </td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#modal_requerimiento" data-id_requerimiento="{{ $inc->id_requerimiento }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}">
                            @if($inc->Detalle=='')    
                                {{ $inc->Detalle }}
                            @else
                                <button class="btn btn-success">{{ $inc->Detalle }}</button>
                            @endif
                            </a>
                        </td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#modal_comentarios" data-id="{{$inc->id}}">
                                <button class="btn btn-dark"><i class="fas fa-comment"></i></button>
                            </a>
                        </td>
                        <!-- Modal Ver -->
                        @if($inc->tipo_solicitud == 'incidencia')
                            @if($inc->posicion == '0')
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#modal_inventario" data-id="{{ $inc->id }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}" data-folio="{{ $inc->folio }}" data-estacion="{{$inc->estacion}}" data-nombre_corto="{{$inc->nombre_corto}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-prioridad="{{ $inc->prioridad }}" data-tipo_solicitud="{{ $inc->tipo_solicitud }}" data-estatus_incidencia="{{ $inc->estatus_incidencia }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}">
                                        <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                    </a>                                
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editar_incidencia" data-id="{{$inc->id}}" data-id_usuario="{{$inc->id_usuario}}" data-folio="{{$inc->folio}}" data-estacion="{{$inc->estacion}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-id_area_estacion="{{$inc->id_area_estacion}}" data-id_equipo="{{$inc->id_equipo}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-id_area_atencion="{{$inc->id_area_atencion}}" data-foto_ruta="{{$inc->foto_ruta}}" data-estatus_incidencia="{{$inc->estatus_incidencia}}" data-tipo_solicitud="{{$inc->tipo_solicitud}}" data-prioridad="{{$inc->prioridad}}">
                                        <button class="btn btn-warning"><i class="fas fa-edit"></i></button>                                        
                                    </a>
                                </td>
                            @else
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#modal_ver" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-tipo_solicitud="{{ $inc->tipo_solicitud }}" data-estatus_incidencia="{{ $inc->estatus_incidencia }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-id_area_atencion="{{ $inc->id_area_atencion }}" data-descripcion="{{ $inc->descripcion }}" data-asunto="{{ $inc->asunto }}" data-id_equipo="{{ $inc->id_equipo }}" data-id_area_estacion="{{ $inc->id_area_estacion }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}" data-folio="{{ $inc->folio }}" data-id="{{ $inc->id }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}">
                                        <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                    </a>                                
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editar_incidencia" data-id="{{$inc->id}}" data-id_usuario="{{$inc->id_usuario}}" data-folio="{{$inc->folio}}" data-estacion="{{$inc->estacion}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-id_area_estacion="{{$inc->id_area_estacion}}" data-id_equipo="{{$inc->id_equipo}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-id_area_atencion="{{$inc->id_area_atencion}}" data-foto_ruta="{{$inc->foto_ruta}}" data-estatus_incidencia="{{$inc->estatus_incidencia}}" data-tipo_solicitud="{{$inc->tipo_solicitud}}" data-prioridad="{{$inc->prioridad}}">
                                        <button class="btn btn-warning"><i class="fas fa-edit"></i></button>
                                    </a>
                                </td>
                            @endif
                        @else
                            <td>
                                <a href="#" data-toggle="modal" data-target="#modal_requerimiento" data-id_requerimiento="{{ $inc->id }}" data-cantidad="{{ $inc->cantidad }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}"  data-folio="{{ $inc->folio }}">
                                    <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                    <!-- &nbsp;Ver -->
                                </a>
                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#editar_incidencia" data-id="{{$inc->id}}" data-id_usuario="{{$inc->id_usuario}}" data-folio="{{$inc->folio}}" data-estacion="{{$inc->estacion}}" data-fecha_incidencia="{{$inc->fecha_incidencia}}" data-id_area_estacion="{{$inc->id_area_estacion}}" data-id_equipo="{{$inc->id_equipo}}" data-asunto="{{$inc->asunto}}" data-descripcion="{{$inc->descripcion}}" data-id_area_atencion="{{$inc->id_area_atencion}}" data-foto_ruta="{{$inc->foto_ruta}}" data-estatus_incidencia="{{$inc->estatus_incidencia}}" data-tipo_solicitud="{{$inc->tipo_solicitud}}" data-prioridad="{{$inc->prioridad}}">
                                    <button class="btn btn-warning" disabled><i class="fas fa-edit"></i></button>
                                    <!-- &nbsp;Requerim. -->
                                </a>
                            </td>
                        @endif
                        
                        <td>
                            <a href="{{URL::action('IncidenciasController@show',$inc->id)}}">
                                <button class="btn btn-danger"><i class="fas fa-list-alt fa-1x"></i></button>
                                <!-- &nbsp;Detalles -->
                            </a>
                        </td>
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

            modal.find('.modal-title').text('Relacionar Requerimiento')
            modal.find('#id').val(id)
            //modal.find('#user').val(user)
            modal.find('#inc_folio').val(folio)
            modal.find('#inc_estacion').val(estacion)
            modal.find('#inc_tipo_solicitud').val(tipo_solicitud)
            modal.find('#inc_prioridad').val(prioridad)
            modal.find('#prioridad').val(prioridad)
            // modal.find('#fecha').val(fecha_incidencia)
            // modal.find('#asunto').val(asunto)
            // modal.find('#descripcion').html(descripcion)
            // modal.find('#foto_ruta_escondida').val(foto_ruta)
            
            modal.find('#estatus_incidencia').val(estatus_incidencia)         
            //$('#div_req *').prop('disabled', true);

            $.get('{{ url("/") }}/requerimiento',{estacion: estacion},function(data){
                $('#requerimiento').empty();                
                $('#requerimiento').append("<option value=''>Selecciona</option>");
                $.each(data,function(fetch, miobj){
                    $('#requerimiento').append('<option value="' + miobj.id + '">' + miobj.folio + '</option>');                
                });
            });

            var req = "requerimiento";
            $.get('{{ url("/") }}/folios',{estacion: estacion, tipo_solicitud: req},function(data){
                modal.find('#folio').val(data);   
            });

            $.get('{{ url("/") }}/catalogo_refaccion',{estacion: estacion},function(data){
                $('#id_catalogo').empty();
                
                $('#id_catalogo').append("<option value=''>Selecciona</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_catalogo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });
            
            $.get('{{ url("/") }}/areas',{estacion: estacion},function(data){
                $('#id_area_estacion').empty();
                $('#id_equipo').empty();
                
                $('#id_area_estacion').append("<option value=''>Selecciona Area de estacion</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_area_estacion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });

            // $.get('{{ url("/") }}/posiciones',{estacion: estacion},function(data)
            // {
            //     modal.find('#posicion').empty();
            //     modal.find('#posicion').append("<option value=''>Selecciona Posicion/Lado</option>");
            //     $.each(data,function(fetch, miobj){
            //         modal.find('#posicion').append('<option value="' + miobj.posicion + 
            //     '">dispensario '+ miobj.id_equipo +', posición '+ miobj.posicion + '</option>');                
            //     });
            // });
            //Evento change de combo catalogo
            $('#id_catalogo').change(event => {
                var id="";
                //var estacion = $("#estacion").val();

                if(estacion=="CORPORATIVO"){
                    id=98;      //ID_CATALOGO PARA REFACCIONES DE CORPORATIVO
                }else{
                    id = $('select[name=id_catalogo]').val();
                }

                $.get('{{ url("/") }}/refacciones',{id: id},function(data){
                    $('#refacciones').empty();
                    $('#refacciones').append("<option value=''>Selecciona Refaccion</option>");
                    $.each(data,function(fetch, miobj){
                        $('#refacciones').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>'); 
                        $("#prioridad").val(miobj.prioridad);       
                        $('#id_area_atencion').append('<option value="' + miobj.id_area_atencion + '">'+''+ '</option>');
                    });
                });

                if(id<=3)   //solo para refacc. dispensarios, disp aire agua y calcomanias
                {            
                    $.get('{{ url("/") }}/posiciones',{estacion: estacion},function(data)
                    {
                        modal.find('#posicion').empty();
                        modal.find('#posicion').append("<option value=''>Selecciona Posicion/Lado</option>");
                        $.each(data,function(fetch, miobj){
                            modal.find('#posicion').append('<option value="' + miobj.posicion + 
                        '">dispensario '+ miobj.id_equipo +', posicion '+ miobj.posicion + '</option>');                
                        });
                    });
                }else{
                    modal.find('#posicion').empty();
                }

            });

        });  
            /*
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

            $.get('{{ url("/") }}/catalogo_refaccion',{estacion: estacion},function(data){
                $('#id_catalogo').empty();
                
                $('#id_catalogo').append("<option value=''>Selecciona</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_catalogo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });
            */


            // $('#relacion').change(event => { 
            //     var opcion=$('select[name=relacion]').val();                

            //     if(opcion=="1"){
            //          $('#div_req *').prop('disabled', true);
            //          $('#requerimiento').prop('disabled', false);
            //     }else{
            //         //$(location).attr('href','{{ url("/") }}/captura_incidencia')
            //         $('#div_req *').prop('disabled', false);
            //         $('#requerimiento').prop('disabled', true);
            //     }
            // }); 
                                    
          

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

             $.get('{{ url("/") }}/requerimiento_detalle',{id: id_req},function(data)
             {
                 $.each(data,function(fetch, miobj){
                     modal.find('.modal-title').text('Requerimiento: ID: ' + miobj.id)
                     modal.find('#user').val(usuario)
                     modal.find('#folio').val(miobj.folio)
                     modal.find('#estacion').val(miobj.estacion+', '+miobj.nombre_corto)
                     modal.find('#fecha_incidencia').val(miobj.fecha_incidencia)
                     modal.find('#area_estacion_descripcion').val(miobj.area_estacion_descripcion)                   
                     modal.find('#area_atencion_descripcion').val(miobj.area_atencion_descripcion)
                     modal.find('#estatus_incidencia').val(miobj.estatus_incidencia)
                     modal.find('#tipo_solicitud').val(miobj.tipo_solicitud)
                     modal.find('#prioridad').val(miobj.prioridad)
                     modal.find('#equipo_descripcion').val(miobj.equipo_descripcion)
                     modal.find('#refaccion_descripcion').val(miobj.refaccion_descripcion)
                     modal.find('#posicion').val(miobj.posicion)
                     modal.find('#foto').attr("src", '{{ url("/") }}/incidencia/imagenes/' + miobj.foto_ruta)
                     modal.find('#cantidad').val(miobj.cantidad)
          
                 });
             });

            

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
        
        $('#modal_inventario').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var usuario= button.data('user')
            var folio = button.data('folio')
            var estacion = button.data('estacion') + ' ' + button.data('nombre_corto')
            var fecha_incidencia = button.data('fecha_incidencia')
            var asunto = button.data('asunto')
            var descripcion = button.data('descripcion')
            var foto_ruta = button.data('foto_ruta')
            var prioridad = button.data('prioridad')
            var equipo_descripcion = button.data('equipo_descripcion')
            var area_atencion_descripcion = button.data('area_atencion_descripcion')
            var area_estacion_descripcion = button.data('area_estacion_descripcion')
            var subarea = button.data('refaccion_descripcion')
            var posicion = button.data('posicion')

            var modal = $(this)

            modal.find('.modal-title').text('Incidencia de Sistemas: ' + id)
            modal.find('#user').val(usuario)
            modal.find('#folio').val(folio)
            modal.find('#estacion').val(estacion)
            modal.find('#fecha_incidencia').val(fecha_incidencia)
            modal.find('#prioridad').val(prioridad)
            modal.find('#equipo_descripcion').val(equipo_descripcion)
            modal.find('#asunto').val(asunto)
            modal.find('#descripcion').val(descripcion)
            modal.find('#area_atencion_descripcion').val(area_atencion_descripcion)
            modal.find('#area_estacion_descripcion').val(area_estacion_descripcion)
            modal.find('#subarea').val(subarea)
            modal.find('#foto').attr("src", '{{ url("/") }}/incidencia/imagenes/' + foto_ruta)
         });

        //para MODAL editar incidencia, se llena en el evento show
        $('#modal_comentarios').on('show.bs.modal', function(event) 
        {        
            //asi lleno el modal editar_incidencia
            var button = $(event.relatedTarget)            
            var id = button.data('id')
            var modal = $(this)
            modal.find('.modal-title').text('Comentarios a la incidencia: ID: ' + id)
            modal.find('#id').val(id)

            var cont = 0;              
              $("#tbl_comentarios tbody tr").remove();
                $.ajax({
                  url: '{{ url("/") }}/index/comentarios/' + id,  
                  success: function(response) {
                      $.each(response.comentarios,function(fetch, miobj){
                          var fila = '<tr id="fila'+cont+'"> <td>'+miobj.name+'</td> <td>'+miobj.comentario+'</td> <td>'+miobj.fecha_captura+'</td> </tr>';
                          $('#tbl_comentarios').append(fila);
                          cont++;
                      });    
                  }
              }); 
        });
         
        
        //Evento change de combo areas estacion dentro del MODAL
        $('#id_area_estacion').change(event => {            
            var estacion = $("#estacion").val();
            var id_area_estacion = $('select[name=id_area_estacion]').val();

            $.get('{{ url("/") }}/equipos',{estacion: estacion, id_area_estacion: id_area_estacion},function(data){
                //console.log(data);
                $('#id_equipo').empty();
                $('#id_area_atencion').empty();
                $('#id_equipo').append("<option value=''>selecciona Equipo/SubArea</option>");
                $.each(data,function(fetch, miobj){
                    $('#id_equipo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
                });
            });

        });

        
                
        //Evento change de combo refacciones
    $('#refacciones').change(event => 
    {
        var id = $('select[name=id_catalogo]').val();
        var estacion =$("#estacion").val();
        var id_refaccion = $('select[name=refacciones]').val();

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

});

    $(function(){  
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
        $("#ob4").hover(function(){
        $("#fig4").css("display","block"); 
        }, function(){
            $("#fig4").css("display","none"); 
        });
    });
</script>
@endsection