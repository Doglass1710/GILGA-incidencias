<div class="modal fade" id="editar_detalle_incidencia" tabindex="" role="dialog" aria-hidden="true">
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
                    <div class="row justify-content-center">            
                        <div class="col-md-12">                
                            <div class="card">
                                                    
                                <div class="card-body">
                                    {!!Form::open(array('action'=>'IncidenciasController@updateDetalleIncidencia','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                                    
                                    <!--<form method="POST" action="IncidenciasController@updateDetalleIncidencia" autocomplete="off" enctype="multipart/form-data">-->
                                        @csrf
                                        <div class="form-group form-row">
                                            <div class="form-group col-md-6">
                                                <label for="fecha_detalle">Fecha</label>
                                                <input type="hidden" name="id" id="id" value="">
                                                <input type="text" class="form-control" id="fecha_detalle" name="fecha_detalle" placeholder="" readonly="">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="comentarios">Comentarios</label>
                                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="comentarios" name="comentarios" required maxlength="255"></textarea>
                                                
                                            </div>   
                                        </div>
                                        
                                        <div class="form-group form-row">
                                            <div class="form-group col-md-12">
                                                <img src="" alt="No hay imagen para mostrar" id="foto" class="img-fluid">
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group form-row">
                                            
                                            <div class="form-group col-md-8">
                                                <label for="foto_ruta">Actualizar foto</label>
                                                <input type="file" class="form-control" id="foto_ruta" name="foto_ruta">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="estatus">Estatus</label>
                                                <select id="estatus" class="form-control" name="estatus">
                                                <option selected>En Proceso</option>
                                                <option>Terminado</option>
                                                <!--<option>Pendiente</option>-->
                                                </select>
                                            </div>

                                        </div>                                     

                                        <div class="form-group form-row">
                                            <div class="ml-auto">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Actualizar Detalle</button>

                                            </div>
                                        </div>                            

                                    <!--</form>-->
                                    {!!Form::close()!!}

                                </div>                    
                            </div>                
                        </div>            
                    </div>        
                </div>
                
            </div>
        </div>
    </div>
</div>
