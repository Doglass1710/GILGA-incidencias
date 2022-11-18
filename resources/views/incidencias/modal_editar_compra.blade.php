<div class="modal fade" id="modal_editar_compra" tabindex="" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                
                <div class="container">        
                    <div class="row">            
                        <div class="col-md-12">                
                            <div class="card">
                                                    
                                <div class="card-body">
                                    
                                    <form method="POST" action="{{ route('editar_compra') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-row">
                                            <div class="form-group col-md-6">
                                              <label for="edit_proveedor">Proveedor</label>
                                              <input type="hidden" name="edit_id_incidencia" id="edit_id_incidencia">
                                              <input type="hidden" name="edit_id" id="edit_id">
                                              <input class="form-control" type="text" name="edit_proveedor" id="edit_proveedor" readonly>
                                              
                                            </div>

                                            <div class="form-group col-md-6">
                                              <label for="edit_facturar_a">Facturar a</label>
                                              <input class="form-control" type="text" name="edit_facturar_a" id="edit_facturar_a" readonly>                                                                      
                                              
                                            </div>
                                            
                                           
                                        </div>

                                        <div class="form-group form-row">                                                                                        
                                            
                                            <div class="form-group col-md-12">
                                                <label for="edit_observaciones">Observaciones</label>
                                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="edit_observaciones" name="edit_observaciones" maxlength="255"></textarea>
                                            </div>                                                                        

                                        </div>                                        
                                        
                                        <!--Aqui empieza el CARD para los detalles de la compra -->
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                <div class="form-group form-row">
                                                    
                                                    <div class="form-group col-md-2">
                                                        <label for="edit_pcantidad">Cantidad</label>
                                                        <input type="number" class="form-control" id="edit_pcantidad" name="edit_pcantidad" placeholder="">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="edit_punidad">Unidad</label>
                                                        <select id="edit_punidad" name="edit_punidad" class="form-control" >
                                                            <option value="pieza">pieza</option>
                                                            <option value="litro">litro</option>
                                                            <option value="metro">metro</option>
                                                            <option value="galon">galon</option>
                                                            <option value="cubeta">cubeta</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label for="edit_pprecio_unitario">Precio Unitario</label>
                                                        <input type="number" class="form-control" id="edit_pprecio_unitario" name="edit_pprecio_unitario" placeholder="">
                                                    </div>                                                    

                                                    <div class="form-group col-md-2">
                                                        <label for="edit_pmoneda">Moneda</label>
                                                        <select id="edit_pmoneda" name="edit_pmoneda" class="form-control" >
                                                            <option value="MXN">MXN</option>
                                                            <option value="USD">USD</option>
                                                            <option value="EUR">EUR</option>
                                                        </select>
                                                    </div> 

                                                    <div class="form-group col-md-2">
                                                        <label for="edit_ptipo_cambio">Tipo Cambio</label>
                                                        <input type="number" class="form-control" id="edit_ptipo_cambio" name="edit_ptipo_cambio" placeholder="" maxlength="6">
                                                    </div>                                        

                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="edit_producto_descripcion">Producto Descripcion</label>
                                                        <input type="text" class="form-control" id="edit_pproducto_descripcion" name="edit_pproducto_descripcion" placeholder="Producto" maxlength="60">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="edit_id_incidencia_detalle">Incidencia</label>                                              
                                                        <select id="edit_id_incidencia_detalle" name="edit_id_incidencia_detalle" class="form-control" >
                                                        @foreach($compras_incidencias as $ci)
                                                            <option value="{{$ci->id}}">{{$ci->incidencia}}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>                                                                                                                                                                                          

                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="ml-auto">                                                        
                                                        <button type="button" class="btn btn-primary" id="edit_btn_add"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Agregar</button>
                                                        
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="form-group col-lg-12">
                                                        
                                                        <div class="table-responsive">
                                                            <table id="editar_detalles" class="table table-striped table-bordered table-condensed table-hover">
                                                                <thead class="thead-dark">
                                                                    <th>ID Incidencia</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Unidad</th>
                                                                    <th>P. Descripcion</th>
                                                                    <th>Tipo Cambio</th>
                                                                    <th>Moneda</th>
                                                                    <th>-----PrecioUnitario-----</th>
                                                                    <th>-----Total-----</th>
                                                                    <th>-----Opciones-----</th>
                                                                </thead>
                                                                
                                                                <tbody>
                                                                    

                                                                </tbody>
                                                                
                                                                <tfoot>                                                                    
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th colspan="2"><input type="text" class="form-control" id="edit_subtotal" name="edit_subtotal" readonly></th>
                                                                    <th colspan="2"><input type="text" class="form-control" id="edit_iva" name="edit_iva" readonly></th>
                                                                    <th colspan="2"><input type="text" class="form-control" id="edit_total_final" name="edit_total_final" readonly></th>
                                                                </tfoot>
                                                            </table>
                                                        
                                                        </div>
                                                    </div>
                                                </div>   

                                            </div>
                                        </div>

                                                               
                                        <div id="edit_guardar">
                                            <div class="form-group form-row">
                                                <div class="ml-auto">
                                                    
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Editar compra</button>
                                                    
                                                </div>
                                            </div> 
                                        </div>                           

                                    </form>                                    

                                </div>                    
                            </div>                
                        </div>            
                    </div>        
                </div>
            
            </div><!--Fin Modal Body-->            
            
        </div>
    </div>
</div>

