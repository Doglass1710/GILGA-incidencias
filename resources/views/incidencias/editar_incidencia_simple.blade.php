
<div class="modal fade" id="editar_incidencia" tabindex="" role="dialog" aria-hidden="true">
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
                            {!!Form::open(array('action'=>'IncidenciasController@update','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                            {{Form::token()}}
                            @csrf
                                <div class="card">                                                    
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                            <label for="tipo_solicitud">Tipo Solicitud</label>
                                            <input type="hidden" name="id" id="id" value="">
                                            <input type="text" class="form-control" id="tipo_solicitud" name="tipo_solicitud" readonly>
                                            </div>

                                            <div class="form-group col-md-4">
                                            <label for="prioridad">Prioridad</label>
                                            <input type="text" class="form-control" id="prioridad" name="prioridad" readonly>
                                            
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="folio">Folio</label>
                                                <input type="text" class="form-control" id="folio" name="folio" readonly>
                                                                                
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                            <label for="estacion">Estacion</label>
                                            <input type="text" class="form-control" id="estacion" name="estacion" placeholder="" readonly>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Selecciona un requerimiento</label>
                                                <select class="form-control" id="requerimiento" name="requerimiento" required>
                                                </select> 
                                            </div>
                                            <div class="form-group col-md-4">

                                            </div>
                                        </div>
                                    </div> 
                                </div>   
                                <br/>
                                <div class="form-group form-row">
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Aceptar</button>                                        
                                    </div>
                                </div>     
                            {!!Form::close()!!}                                
                        </div>            
                    </div>        
                </div>
            
            </div><!--Fin Modal Body-->            
            
        </div>
    </div>
</div>


