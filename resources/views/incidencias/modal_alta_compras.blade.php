<div class="modal fade" id="modal_alta_compras" tabindex="" role="dialog" aria-hidden="true">
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
                                    
                                    <form method="POST" action="{{ route('alta_compras') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-row">
                                            <div class="form-group col-md-6">
                                              <label for="proveedor">Proveedor</label>
                                              <input type="hidden" name="id_incidencia" id="id_incidencia" value="">
                                              <select id="proveedor" name="proveedor" class="form-control">
                                                @foreach($proveedores as $prov)
                                                    <option value="{{$prov->proveedor}}">{{$prov->razon_social}}</option>
                                                @endforeach 
                                              </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                              <label for="facturar_a">Facturar a</label>                                              
                                              <select id="facturar_a" name="facturar_a" class="form-control" >
                                                  @foreach($companias as $comp)
                                                    <option value="{{$comp->id}}">{{$comp->razon_social}}</option>
                                                  @endforeach
                                              </select>                                     
                                              
                                            </div>
                                            
                                            <!--<div class="form-group col-md-4">
                                                <label for="folio">Folio</label>
                                                <input type="text" class="form-control" id="folio" name="folio">
                                                                                  
                                            </div>-->
                                        </div>

                                        <div class="form-group form-row">                                                                                        
                                            
                                            <div class="form-group col-md-12">
                                                <label for="observaciones">Observaciones</label>
                                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="observaciones" name="observaciones" maxlength="255"></textarea>
                                            </div>                                                                        

                                        </div>                                        
                                        
                                        <!--Aqui empieza el CARD para los detalles de la compra -->
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                <div class="form-group form-row">
                                                    
                                                    <div class="form-group col-md-2">
                                                        <label for="pcantidad">Cantidad</label>
                                                        <input type="number" class="form-control" id="pcantidad" name="pcantidad" >
                                                    </div>
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="punidad">Unidad</label>
                                                        <select id="punidad" name="punidad" class="form-control" >
                                                            <option value="pieza">pieza</option>
                                                            <option value="litro">litro</option>
                                                            <option value="metro">metro</option>
                                                            <option value="galon">galon</option>
                                                            <option value="cubeta">cubeta</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label for="pprecio_unitario">Precio Unitario</label>
                                                        <input type="number" class="form-control" id="pprecio_unitario" name="pprecio_unitario" >
                                                    </div>                                                    

                                                    <div class="form-group col-md-2">
                                                        <label for="pmoneda">Moneda</label>
                                                        <select id="pmoneda" name="pmoneda" class="form-control" >
                                                            <option value="MXN">MXN</option>
                                                            <option value="USD">USD</option>
                                                            <option value="EUR">EUR</option>
                                                        </select>
                                                    </div>                                            
                                                    
                                                    <div class="form-group col-md-2">
                                                        <label for="ptipo_cambio">Tipo Cambio</label>
                                                        <input type="number" class="form-control" id="ptipo_cambio" name="ptipo_cambio"  maxlength="6">
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="producto_descripcion">Producto Descripcion</label>
                                                        <input type="text" class="form-control" id="pproducto_descripcion" name="pproducto_descripcion" placeholder="Producto" maxlength="60">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="id_incidencia_detalle">Incidencia</label>                                              
                                                        <select id="id_incidencia_detalle" name="id_incidencia_detalle" class="form-control" >
                                                        @foreach($compras_incidencias as $ci)
                                                            <option value="{{$ci->id}}">{{$ci->incidencia}}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>                                                                                                                                                                                          

                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="ml-auto">                                                        
                                                        <button type="button" class="btn btn-primary" id="btn_add"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Agregar</button>
                                                        
                                                    </div>
                                                </div>

                                                <div class="form-group form-row">
                                                    <div class="form-group col-lg-12">
                                                        
                                                        <div class="table-responsive">
                                                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
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
                                                                    <th colspan="2"><input type="text" class="form-control" id="subtotal" name="subtotal" readonly></th>
                                                                    <th colspan="2"><input type="text" class="form-control" id="iva" name="iva" readonly></th>
                                                                    <th colspan="2"><input type="text" class="form-control" id="total_final" name="total_final" readonly></th>
                                                                   
                                                                </tfoot>
                                                            </table>
                                                        
                                                        </div>
                                                    </div>
                                                </div>   

                                            </div>
                                        </div>

                                                               
                                        <div id="guardar">
                                            <div class="form-group form-row">
                                                <div class="ml-auto">
                                                    
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Solicitar compra</button>
                                                    
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

