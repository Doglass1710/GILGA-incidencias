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
            <h3>Listado de Incidencias Cerradas</h3>
            @include('incidencias.modal_requerimiento')
            @include('incidencias.modal_ver')
            @include('incidencias.modal_detalles_inc_cerradas')
            
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table id="tableIncCerradas" 
                data-order='[[ 7, "desc" ]]' data-page-length='25'
                class="table table-light  table-bordered table-condensed table-hover">
                    <thead class="thead-dark">
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Folio</th>
                        <th>Estacion</th>
                        <th>Nombre Estacion</th>
                        <th>Zona</th>
                        <th>Fecha Incidencia</th>
                        <th>Fecha Cierre</th>
                        <th>Dias Vida Incidencia</th>
                        <!--<th>id area estacion</th>-->
                        <!--<th>id equipo</th>-->
                        <th>Asunto</th>
                        <th>Detalle</th>
                        <th>Ver</th>                        
                        <th>Detalles</th>
                        @if(\Auth::user()->role == 'admin')
                        <th>Informacion</th>
                        @endif
                    </thead>
                    @foreach ($incidencias_cerradas as $inc)
                    <tr>
                    <!--<tr class="{{ $inc->prioridad == 'alta' ? 'table-danger' : 'table-light' }}">-->   
                        <td>{{ $inc->id }}</td>
                        <td>{{ $users->where('id', $inc->id_usuario)->first()->name }}</td>
                        <td>{{ $inc->folio }}</td>
                        <td>{{ $inc->estacion }}</td>
                        <td>{{ $inc->nombre_corto }}</td>
                        <td>{{ $inc->zona }}</td>
                        <td>{{ $inc->fecha_incidencia }}</td>
                        <td>{{ $inc->fecha_cierre }}</td>
                        <td>{{ $inc->dias_vida_incidencia }}</td>  
                        <td>{{ $inc->asunto }}</td>
                        <td>{{ $inc->Detalle }}</td>
                        
                        @if($inc->tipo_solicitud == 'incidencia')
                            <td>
                                <a href="#" data-toggle="modal" data-target="#modal_ver" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-tipo_solicitud="{{ $inc->tipo_solicitud }}" data-estatus_incidencia="{{ $inc->estatus_incidencia }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-id_area_atencion="{{ $inc->id_area_atencion }}" data-descripcion="{{ $inc->descripcion }}" data-asunto="{{ $inc->asunto }}" data-id_equipo="{{ $inc->id_equipo }}" data-id_area_estacion="{{ $inc->id_area_estacion }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}" data-folio="{{ $inc->folio }}" data-id="{{ $inc->id }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}"><button class="btn btn-info"><i class="fas fa-eye"></i>&nbsp;Ver</button></a>
                            </td>
                        @else
                            <td>
                                <a href="#" data-toggle="modal" data-target="#modal_requerimiento" data-id_requerimiento="{{ $inc->id }}" data-user="{{ $users->where('id', $inc->id_usuario)->first()->name }}" data-posicion="{{ $inc->posicion }}" data-area_estacion_descripcion="{{ $inc->area_estacion_descripcion }}" data-area_atencion_descripcion="{{ $inc->area_atencion_descripcion }}" data-equipo_descripcion="{{ $inc->equipo_descripcion }}" data-refaccion_descripcion="{{ $inc->refaccion_descripcion }}" data-prioridad="{{ $inc->prioridad }}" data-foto_ruta="{{ $inc->foto_ruta }}" data-fecha_incidencia="{{ $inc->fecha_incidencia }}" data-estacion="{{ $inc->estacion }}"  data-folio="{{ $inc->folio }}"><button class="btn btn-info"><i class="fas fa-eye"></i>&nbsp;Ver</button></a>
                            </td>
                        @endif
                        <td>
                            <a href="#" data-toggle="modal" data-target="#modal_detalles_inc_cerradas" data-id="{{ $inc->id }}" >
                                <button class="btn btn-danger"><i class="fas fa-list-alt"></i>&nbsp;Detalles</button>
                            </a>
                        </td> 
                        @if(\Auth::user()->role == 'admin')
                        <td>
                            <a href="{{URL::action('IncidenciasController@show',$inc->id)}}">
                                <button class="btn btn-success">
                                    <i class="fas fa-list-alt fa-1x"></i>&nbsp;+Informacion
                                </button>
                            </a>
                        </td>  
                        @endif
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
        
        $('#tableIncCerradas').DataTable({
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
        });
        
        
        //$("#estatus_incidencia").val("ABIERTA"); 

        //para MODAL ver detalles incidencia, se llena en el evento show
        $('#modal_detalles_inc_cerradas').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)

            var id = button.data('id')           

            var modal = $(this)

            modal.find('.modal-title').text('Incidencia: ID: ' + id) 
            var cont = 0;              
            $("#detalle_incidencias tbody tr").remove();
            $.ajax({
                type: "GET",
                url: '{{ url("/") }}/incidencias_cerradas/detalles/' + id,                
                success: function(response) {
                    $.each(response.detalle_inc,function(fetch, miobj){
                        var fila = '<tr id="fila'+cont+'"> <td>'+miobj.usuario+'</td> <td>'+miobj.fecha_detalle_incidencia+'</td> <td>'+miobj.comentarios+'</td> <td><a href=" {{ url("/") }}/incidencias_cerradas/detalles/descargaimagenes/' + miobj.foto_ruta+' ">'+miobj.foto_ruta+'</a></td> <td>'+miobj.estatus+'</td> </tr>';
                        $('#detalle_incidencias').append(fila);
                        cont++;
                    });    
                }
            });
        });   
          
        

    });
</script>
@endsection

