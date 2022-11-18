@extends('layouts.app')

@section('content')

<div class="container">
    @include('incidencias.editar_detalle_incidencia')
    @include('incidencias.modal_alta_compras')
    @include('incidencias.modal_editar_compra')
    @include('incidencias.modal_autoriza_compra')
    @include('incidencias.modal_elimina_compra')
    @include('incidencias.modal_cerrar_compra')
    @include('incidencias.modal_denegar_compra')
    @include('incidencias.modal_ver_detalle_inc')
    <!--esta es la vista donde se ve el listado de detalles de la incidencia-->
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="row">
        
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-12 mb-4">
            <h3>Detalle de Incidencia&nbsp;
            @if($permisos->where("elemento","boton_alta_compra")->count()>0)
                <a href="#" data-toggle="modal" data-target="#modal_alta_compras" data-id_incidencia="{{$id_incidencia}}"><button class="btn btn-primary"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Generar Orden de Compra</button></a>&nbsp;
            @endif    
                <a href="{{ route('detalle', $id_incidencia) }}"><button class="btn btn-success"><i class="fas fa-file-alt fa-1x"></i>&nbsp;Capturar Detalle</button></a> 
            
            </h3>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table class="table table-light  table-bordered table-condensed table-hover">
                    <thead class="thead-dark" style="text-transform: uppercase">
                        <th>Id Detalle </th>
                        <th>Id Incidencia</th>
                        <th>Usuario</th>
                        <th>Fecha detalle Incidencia</th>
                        <th>Comentarios</th>
                        <th>Estatus</th>
                        <th>Editar</th>
                        <th>Opciones</th>
                    </thead>
                    @foreach ($detalles as $det)
                    <tr>
                        <td>{{ $det->id }}</td>
                        <td>{{ $det->id_incidencia }}</td>
                        <td>{{ $users->where("id", $det->id_usuario)->first()->name }}</td>
                        <td>{{ $det->fecha_detalle_incidencia }}</td>
                        <td>{{ $det->comentarios }}</td>
                        <td>{{ $det->estatus }}</td>
                        
                        @if($det->id_usuario == Auth::id())
                            <td>
                                <a href="#" data-toggle="modal" data-target="#editar_detalle_incidencia" data-id="{{$det->id}}" data-id_incidencia="{{$det->id_incidencia}}" data-id_usuario="{{$det->id_usuario}}" data-fecha_detalle_incidencia="{{$det->fecha_detalle_incidencia}}" data-comentarios="{{$det->comentarios}}" data-foto_ruta="{{$det->foto_ruta}}" data-estatus="{{$det->estatus}}"><button class="btn btn-warning"><i class="fas fa-edit fa-1x"></i>&nbsp;Editar</button></a>

                            </td>
                        @else
                            <td>
                                <a ><button class="btn btn-warning" disabled><i class="fas fa-edit fa-1x"></i>&nbsp;Editar</button></a>

                            </td>
                        @endif
                        
                        @if($det->foto_ruta!=null)
                            <td><a href="" data-toggle="modal" data-target="#modal_ver_detalle_inc" data-id="{{$det->id}}" data-foto_ruta="{{$det->foto_ruta}}"><button class="btn btn-square" title="Ver Foto"><i class="fas fa-image fa-1x"></i></button></a></td>
                            
                        @else
                            <td></td>
                        @endif
                    </tr>
                    @endforeach
                </table>

            </div>
            {{ $detalles->render() }}
        </div>
    </div>
    
    @if(\Auth::user()->role == 'admin')
    <hr>
    
    
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-12 mb-4">
            <h3>Compras:</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table class="table table-light  table-bordered table-condensed table-hover">
                    <thead class="thead-dark" style="text-transform: uppercase">
                        
                        <th>Usuario</th>
                        
                        <th>Fecha Solicitud</th>
                        <th>Folio</th>
                        <!--<th>Cantidad</th>-->
                        
                        <!--<th>Precio Unitario</th>-->
                        <!--<th>Producto</th>-->
                        <!--<th>Total</th>-->
                        <th>Autorizada</th>
                        <th>Cerrada</th>
                        <th>Ver Orden</th>
                        <th>Opciones</th>
                        <th>Acciones</th>
                    </thead>
                    @foreach ($compras as $compra)
                    <tr>
                        
                        <td>{{ $users->where("id", $compra->id_usuario)->first()->name }}</td>
                        
                        <td>{{ $compra->fecha_compra }}</td>
                        <td>{{ $compra->folio }}</td>
                        
                        <td>{{ $compra->autorizada_sn }}</td>
                        <td>{{ $compra->cerrada_sn }}</td>

                        <td>
                            <a href="{{ route('rptordencompra',$compra->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="far fa-file-pdf fa-1x"></i>&nbsp;Ver PDF
                            </a>

                        </td>

                        <td>
                            @if($compra->autorizada_sn == 'NO')
                            <a href="#" data-toggle="modal" data-target="#modal_editar_compra" data-id="{{$compra->id}}" data-id_incidencia="{{$compra->id_incidencia}}" data-proveedor="{{ $proveedores->where('proveedor', $compra->proveedor)->first()->razon_social }}" data-facturar_a="{{ $companias->where('id', $compra->facturar_a)->first()->razon_social}}" data-folio="{{$compra->folio}}" data-observaciones="{{$compra->observaciones}}" data-subtotal="{{$compra->subtotal}}" data-iva="{{$compra->iva}}" data-total="{{$compra->total}}">
                                <button class="btn btn-square" title="Editar"><i class="fas fa-edit fa-1x"></i></button>
                            </a>

                            <a href="#" data-toggle="modal" data-target="#modal_elimina_compra" data-id_compra="{{$compra->id}}" data-id_incidencia="{{$compra->id_incidencia}}" data-proveedor="{{ $proveedores->where('proveedor', $compra->proveedor)->first()->razon_social }}" data-facturar_a="{{ $companias->where('id', $compra->facturar_a)->first()->razon_social}}" data-folio="{{$compra->folio}}" data-observaciones="{{$compra->observaciones}}" data-subtotal="{{$compra->subtotal}}" data-iva="{{$compra->iva}}" data-total="{{$compra->total}}">
                                <button class="btn btn-square" title="Eliminar"><i class="fas fa-trash-alt fa-1x"></i></button>
                            </a>
                            @else
                            <a>
                                <button class="btn btn-square" disabled title="Editar"><i class="fas fa-edit fa-1x"></i></button>
                            </a>

                            <a>
                                <button class="btn btn-square" disabled title="Eliminar"><i class="fas fa-trash-alt fa-1x"></i></button>
                            </a>
                            @endif
                        </td>
                        
                        @if($compra->autorizada_sn == 'NO')
                        
                            @if($permisos->where("elemento","boton_autorizar_compra")->count()>0)
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#modal_autoriza_compra" data-id_compra="{{$compra->id}}" data-id_incidencia="{{$compra->id_incidencia}}"><button  class="autorizar_compra btn btn-success" id="boton_autorizar_compra" name="boton_autorizar_compra"><i class="fas fa-key fa-1x"></i>&nbsp;Autorizar</button></a>

                                </td>
                            
                            @endif
                        @else
                            <td>
                            @if($compra->cerrada_sn == 'NO')
                                @if($permisos->where("elemento","boton_denegar_compra")->count()>0) 
                                <a href="#" data-toggle="modal" data-target="#modal_denegar_compra" data-id_compra="{{$compra->id}}" data-id_incidencia="{{$compra->id_incidencia}}"><button class="denegar_compra btn btn-danger" id="boton_denegar_compra" name="boton_denegar_compra" ><i class="fas fa-minus-circle fa-1x"></i>&nbsp;Denegar</button></a> 
                                @endif

                                @if($permisos->where("elemento","boton_cerrar_compra")->count()>0)
                                <a href="#" data-toggle="modal" data-target="#modal_cerrar_compra" data-id_compra="{{$compra->id}}" data-id_incidencia="{{$compra->id_incidencia}}"><button class="cerrar_compra btn btn-info" id="boton_cerrar_compra" name="boton_cerrar_compra" ><i class="fas fa-lock fa-1x"></i>&nbsp;Cerrar</button></a>
                                @endif
                            @endif    
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </table>

            </div>
            {{ $compras->render() }}
        </div>
    </div>
    @endif
    <hr>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-12 mb-4">
            <h3>Usuarios que visualizaron la incidencia:</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table class="table table-light  table-bordered table-condensed table-hover">
                    <thead class="thead-dark" style="text-transform: uppercase">
                        <th>id usuario</th>
                        <th>nombre usuario</th>
                        <th>email usuario</th>
                        <th>Estatus</th>
                        <th>Fecha de visualizacion</th>
                    </thead>
                    @foreach ($log as $l)
                    <tr>
                        <td>{{ $l->id_usuario }}</td>
                        <td>{{ $users->where("id", $l->id_usuario)->first()->name }}</td>
                        <td>{{ $users->where("id", $l->id_usuario)->first()->email }}</td>
                        <td>{{ $l->estatus }}</td>
                        <td>{{ $l->created_at }}</td>
                    </tr>
                    @endforeach
                </table>

            </div>
            {{ $log->render() }}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-12 mb-4">
            <a href="/listado_incidencias"><button class="btn btn-success"><i class="fas fa-hand-point-left fa-1x"></i>&nbsp;Regresar</button></a>
        </div>
        
    </div>
</div>

@endsection

@section('script')
<script>
    total = [];
    var cont = 0;
    var total_final = 0;
    var subtotal = 0;
    var iva = 0;
    $("#guardar").hide();
    $("#edit_guardar").hide();
    function limpiar(){
        
        $("#pproducto_descripcion").val("");
        $("#pprecio_unitario").val("");
        $("#ptipo_cambio").val("");        
        $("#pcantidad").val("");
    }

    function edit_limpiar(){
        
        $("#edit_pproducto_descripcion").val("");
        $("#edit_pprecio_unitario").val("");
        $("#edit_ptipo_cambio").val("");        
        $("#edit_pcantidad").val("");
    }

    /*debo hacer evaluar para editar*/ 
    function evaluar(){
        if(total_final>0){
            $("#guardar").show();
        }else{
            $("#guardar").hide();
        }
    }

    function edit_evaluar(){
        if(total_final>0){
            $("#edit_guardar").show();
        }else{
            $("#edit_guardar").hide();
        }
    }

    function eliminar(index){
        subtotal = subtotal - total[index];
        var iva_aux = subtotal * 0.16;
        var total_final_aux = subtotal + iva_aux;

        $("#subtotal").val(subtotal);
        $("#iva").val(iva_aux);
        $("#total_final").val(total_final_aux);
        total_final = total_final_aux;
        
        $("#fila"+index).remove();
        evaluar();
        
        //console.log("totalfinal");
        //console.log(total_final);
    }

    function edit_eliminar(index){
        subtotal = subtotal - total[index];
        var iva_aux = subtotal * 0.16;
        var total_final_aux = subtotal + iva_aux;        
                
        $("#edit_subtotal").val(subtotal);
        $("#edit_iva").val(iva_aux);
        $("#edit_total_final").val(total_final_aux);
        total_final = total_final_aux;
        $("#edit_fila"+index).remove();
        edit_evaluar();
        
        //console.log("totalfinal");
        //console.log(total_final);
    }

    function agregar(){
        id_incidencia_detalle = $("#id_incidencia_detalle").val();
        producto_descripcion = $("#pproducto_descripcion").val();
        precio_unitario = parseFloat($("#pprecio_unitario").val());

        tipo_cambio = $("#ptipo_cambio").val();
        moneda = $("#pmoneda").val();

        unidad = $("#punidad").val();
        cantidad = parseFloat($("#pcantidad").val());
        total[cont] = precio_unitario * cantidad; 

        //Debo validar que Cantidad,precio_unitario y tipo_cambio sean numeros
        var pcantidad = parseFloat(document.querySelector("#pcantidad").value);
        var pprecio_unitario = parseFloat(document.querySelector("#pprecio_unitario").value);
        var ptipo_cambio = parseFloat(document.querySelector("#ptipo_cambio").value);

        if(isNaN(pcantidad) || pcantidad == null){
            alert("Introduce una cantidad valida");
            return false;
        }

        if(isNaN(pprecio_unitario) || pprecio_unitario == null){
            alert("Introduce un precio unitario valido");
            return false;
        }

        //solo se debe agregar tipo de cambio si la moneda es diferente de MXN
        if (document.querySelector("#pmoneda").value != "MXN"){
            
            if(isNaN(ptipo_cambio) || ptipo_cambio == null){
                alert("Introduce un tipo de cambio valido");
                return false;
            }
        }

        if(producto_descripcion!="" && unidad!=""){
            subtotal = parseFloat(subtotal) + parseFloat(total[cont]);
            iva = subtotal * 0.16;
            total_final = subtotal + iva;

            

            subtotal = subtotal.toFixed(2);
            iva = iva.toFixed(2);
            total_final = total_final.toFixed(2);

            //console.log(subtotal,iva,total_final);
            
            var fila = '<tr class="selected" id="fila'+cont+'"><td><input type="text" class="form-control" name="id_incidencia_detalle[]" value="'+id_incidencia_detalle+'" readonly></td><td><input type="text" class="form-control" name="cantidad[]" value="'+cantidad+'" readonly></td><td><input type="text" class="form-control" name="unidad[]" value="'+unidad+'" readonly></td><td><input type="text" class="form-control" name="producto_descripcion[]" value="'+producto_descripcion+'" readonly></td><td><input type="text" class="form-control" name="tipo_cambio[]" value="'+tipo_cambio+'" readonly></td><td><input type="text" class="form-control" name="moneda[]" value="'+moneda+'" readonly></td><td><input type="text" class="form-control" name="precio_unitario[]" value="'+precio_unitario+'" readonly></td><td><input type="text" class="form-control" name="total[]" value="'+total[cont]+'" readonly></td><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');"><i class="far fa-trash-alt fa-1x"></i></button></td></tr>';
            cont++;
            limpiar();

            $("#subtotal").val(subtotal);
            $("#iva").val(iva);
            $("#total_final").val(total_final);
            evaluar();
            
            $('#detalles').append(fila);

            //debo borrar el value de id_incidencia_detalle donde el value sea el id_incidencia
            var selectobject = document.getElementById("id_incidencia_detalle");
            for (var i=0; i<selectobject.length; i++) {
                if (selectobject.options[i].value == id_incidencia_detalle)
                    selectobject.remove(i);
            }
        
        }else{
            alert("Verifique los detalles de la compra");
        }

        
    }

    function edit_agregar(){
        id_incidencia_detalle = $("#edit_id_incidencia_detalle").val();
        producto_descripcion = $("#edit_pproducto_descripcion").val();
        precio_unitario = parseFloat($("#edit_pprecio_unitario").val());

        tipo_cambio = $("#edit_ptipo_cambio").val();
        moneda = $("#edit_pmoneda").val();

        unidad = $("#edit_punidad").val();
        cantidad = parseFloat($("#edit_pcantidad").val());
        total[cont] = precio_unitario * cantidad; 

        //Debo validar que Cantidad,precio_unitario y tipo_cambio sean numeros
        var edit_pcantidad = parseFloat(document.querySelector("#edit_pcantidad").value);
        var edit_pprecio_unitario = parseFloat(document.querySelector("#edit_pprecio_unitario").value);
        var edit_ptipo_cambio = parseFloat(document.querySelector("#edit_ptipo_cambio").value);

        if(isNaN(edit_pcantidad) || edit_pcantidad == null){
            alert("Introduce una cantidad valida");
            return false;
        }

        if(isNaN(edit_pprecio_unitario) || edit_pprecio_unitario == null){
            alert("Introduce un precio unitario valido");
            return false;
        }

        //solo se debe agregar tipo de cambio si la moneda es diferente de MXN
        if (document.querySelector("#edit_pmoneda").value != "MXN"){
            
            if(isNaN(edit_ptipo_cambio) || edit_ptipo_cambio == null){
                alert("Introduce un tipo de cambio valido");
                return false;
            }
        }            

        if(producto_descripcion != "" && unidad != ""){
            subtotal = parseFloat(subtotal) + parseFloat(total[cont]);
            iva = subtotal * 0.16;
            total_final = subtotal + iva;

            subtotal = subtotal.toFixed(2);
            iva = iva.toFixed(2);
            total_final = total_final.toFixed(2);

            //console.log(subtotal,iva,total_final);
            
            var edit_fila = '<tr class="selected" id="edit_fila'+cont+'"><td><input type="text" class="form-control" name="edit_id_incidencia_detalle[]" value="'+id_incidencia_detalle+'" readonly></td><td><input type="text" class="form-control" name="edit_cantidad[]" value="'+cantidad+'" readonly></td><td><input type="text" class="form-control" name="edit_unidad[]" value="'+unidad+'" readonly></td><td><input type="text" class="form-control" name="edit_producto_descripcion[]" value="'+producto_descripcion+'" readonly></td><td><input type="text" class="form-control" name="edit_tipo_cambio[]" value="'+tipo_cambio+'" readonly></td><td><input type="text" class="form-control" name="edit_moneda[]" value="'+moneda+'" readonly></td><td><input type="text" class="form-control" name="edit_precio_unitario[]" value="'+precio_unitario+'" readonly></td><td><input type="text" class="form-control" name="edit_total[]" value="'+total[cont]+'" readonly></td><td><button type="button" class="btn btn-warning" onclick="edit_eliminar('+cont+');"><i class="far fa-trash-alt fa-1x"></i></button></td></tr>';
            cont++;
            edit_limpiar();
              

            $("#edit_subtotal").val(subtotal);
            $("#edit_iva").val(iva);
            $("#edit_total_final").val(total_final);
            edit_evaluar();
            
            $('#editar_detalles').append(edit_fila);

            //debo borrar el value de id_incidencia_detalle donde el value sea el id_incidencia
            var selectobject = document.getElementById("edit_id_incidencia_detalle");
            for (var i=0; i<selectobject.length; i++) {
                if (selectobject.options[i].value == id_incidencia_detalle)
                    selectobject.remove(i);
            }
        }else{
            alert("Verifique los detalles de la compra");
        }

        
    }

    $(document).ready(function() {

               
        $('#btn_add').click(function() {
            
            agregar();
        });

        $('#edit_btn_add').click(function() {
            
            edit_agregar();
        }); 

        //para MODAL modal_ver_compra, se llena en el evento show
        $('#modal_editar_compra').on('show.bs.modal', function(event) {
            //asi lleno el modal editar_incidencia
            var button = $(event.relatedTarget)
            
            var id = button.data('id')
            var id_incidencia = button.data('id_incidencia')
            var proveedor = button.data('proveedor')
            var facturar_a = button.data('facturar_a')   
            var observaciones = button.data('observaciones')

            var btnsubtotal = button.data('subtotal')
            var btniva = button.data('iva')
            var btntotal = button.data('total')            
            
            var modal = $(this)
            
            modal.find('.modal-title').text('Incidencia ID: ' + id_incidencia)
            
            modal.find('#edit_id').val(id)
            modal.find('#edit_id_incidencia').val(id_incidencia) 
            modal.find('#edit_proveedor').val(proveedor)
            modal.find('#edit_facturar_a').val(facturar_a)   
            modal.find('#edit_observaciones').html(observaciones)

            modal.find('#edit_subtotal').val(btnsubtotal)  
            modal.find('#edit_iva').val(btniva)  
            modal.find('#edit_total_final').val(btntotal)  
            
            $("#editar_detalles tbody tr").remove();
            //var cont = 0;
            $.ajax({
                url:'/incidencias/compras_detalle/'+ id,
                type:'GET',
                success: function(response){
                    
                    subtotal = 0;
                    iva = 0;
                    total_final = 0;
                    
                    $.each(response.compras_detalle,function(fetch, miobj){
                        
                        //console.log("/*******Detalles**********/");
                        //console.log(miobj.id);
                        //console.log(miobj.id_incidencia);
                        //console.log(miobj.producto_descripcion);
                        //console.log(miobj.cantidad);
                        //console.log(miobj.total);
                        //console.log(cont);
                        //console.log("/********Fin*********/");
                        
                        

                        total[cont] = miobj.total;
                        subtotal = subtotal + total[cont];
                        iva = subtotal * 0.16;
                        total_final = subtotal + iva;
                        var fila = '<tr class="selected" id="edit_fila'+cont+'"><td><input type="text" class="form-control" name="edit_id_incidencia_detalle[]" value="'+miobj.id_incidencia+'" readonly></td><td><input type="text" class="form-control" name="edit_cantidad[]" value="'+miobj.cantidad+'" readonly></td><td><input type="text" class="form-control" name="edit_unidad[]" value="'+miobj.unidad+'" readonly></td><td><input type="text" class="form-control" name="edit_producto_descripcion[]" value="'+miobj.producto_descripcion+'" readonly></td><td><input type="text" class="form-control" name="edit_tipo_cambio[]" value="'+miobj.tipo_cambio+'" readonly></td><td><input type="text" class="form-control" name="edit_moneda[]" value="'+miobj.moneda+'" readonly></td><td><input type="text" class="form-control" name="edit_precio_unitario[]" value="'+miobj.precio_unitario+'" readonly></td><td><input type="text" class="form-control" name="edit_total[]" value="'+total[cont]+'" readonly></td><td><button type="button" class="btn btn-warning" onclick="edit_eliminar('+cont+');"><i class="far fa-trash-alt fa-1x"></i></button></td></tr>';
                        $('#editar_detalles').append(fila);
                        cont++;
                    });

                }
            });

              
            
        });

        //para MODAL ver detalle_incidencia, se llena en el evento show
        $('#modal_ver_detalle_inc').on('show.bs.modal', function(event) {
                            
            //asi lleno el modal editar_incidencia
            var button = $(event.relatedTarget)
            
            var id = button.data('id')
            //var fecha_detalle_incidencia = button.data('fecha_detalle_incidencia')
            //var comentarios = button.data('comentarios')
            //var estatus = button.data('estatus')
            var foto_ruta = button.data('foto_ruta')
            
            var modal = $(this)
            
            //modal.find('#id').val(id)
            modal.find('.modal-title').text('Imagen Detalle Incidencia: ID-> ' + id)
            //modal.find('#fecha_detalle').val(fecha_detalle_incidencia)
            //modal.find('#comentarios').html(comentarios)
            //modal.find('#estatus').val(estatus)
            modal.find('#foto_detalle').attr("src", '{{ url("/") }}/incidencias/detalles/imagenes/' + foto_ruta)
        }); 
        
        //para MODAL editar incidencia, se llena en el evento show
        $('#editar_detalle_incidencia').on('show.bs.modal', function(event) {
                            
            //asi lleno el modal editar_incidencia
            var button = $(event.relatedTarget)
            
            var id = button.data('id')
            var fecha_detalle_incidencia = button.data('fecha_detalle_incidencia')
            var comentarios = button.data('comentarios')
            var estatus = button.data('estatus')
            var foto_ruta = button.data('foto_ruta')
            
            var modal = $(this)
            
            modal.find('#id').val(id)
            modal.find('.modal-title').text('Editar Detalle Incidencia: ID-> ' + id)
            modal.find('#fecha_detalle').val(fecha_detalle_incidencia)
            modal.find('#comentarios').html(comentarios)
            modal.find('#estatus').val(estatus)
            modal.find('#foto').attr("src", '{{ url("/") }}/incidencia/detalleimagenes/' + foto_ruta)
        }); 
        
        
        $('#modal_alta_compras').on('show.bs.modal', function(event) {
                            
            var button = $(event.relatedTarget)
            
            var id_incidencia = button.data('id_incidencia')
            
            
            var modal = $(this)
            modal.find('.modal-title').text('Incidencia ID: ' + id_incidencia)
            
            modal.find('#id_incidencia').val(id_incidencia)

            subtotal = 0;
            iva = 0;
            total_final = 0;
            $("#guardar").hide();
            $("#detalles tbody tr").remove();

            $("#subtotal").val(subtotal);
            $("#iva").val(iva);
            $("#total_final").val(total_final);
            
        });
        
        $('#modal_autoriza_compra').on('show.bs.modal', function(event) {
                            
            
            var button = $(event.relatedTarget)
            
            var id_incidencia = button.data('id_incidencia')
            var id_compra = button.data('id_compra')
            
            
            var modal = $(this)
            
            modal.find('#id_incidencia').val(id_incidencia)
            modal.find('#id_compra').val(id_compra)
            
        }); 

        $('#modal_cerrar_compra').on('show.bs.modal', function(event) {
                            
            
            var button = $(event.relatedTarget)
            
            var id_incidencia = button.data('id_incidencia')
            var id_compra = button.data('id_compra')
            
            
            var modal = $(this)
            
            modal.find('#cerrar_id_incidencia').val(id_incidencia)
            modal.find('#cerrar_id_compra').val(id_compra)
                            
        });

        $('#modal_denegar_compra').on('show.bs.modal', function(event) {
                            
            
            var button = $(event.relatedTarget)
            
            var id_incidencia = button.data('id_incidencia')
            var id_compra = button.data('id_compra')
            
            
            var modal = $(this)
            
            modal.find('#den_id_incidencia').val(id_incidencia)
            modal.find('#den_id_compra').val(id_compra)
                                            
        });

        $('#modal_elimina_compra').on('show.bs.modal', function(event) {
                            
            
            var button = $(event.relatedTarget)
            
            var id_incidencia = button.data('id_incidencia')
            var id_compra = button.data('id_compra')
            
            
            var modal = $(this)
            
            modal.find('#del_id_incidencia').val(id_incidencia)
            modal.find('#del_id_compra').val(id_compra)
            
        });       
        
        
    });
</script>
@endsection