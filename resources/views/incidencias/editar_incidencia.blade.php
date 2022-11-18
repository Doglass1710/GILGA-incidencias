
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
                                        <div class="form-group col-md-3">
                                            <label>Tipo Solicitud</label>
                                            <input type="hidden" name="id" id="id" value="">
                                            <input type="text" class="form-control" id="inc_tipo_solicitud" name="inc_tipo_solicitud" readonly>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="prioridad">Prioridad</label>
                                            <input type="text" class="form-control" id="inc_prioridad" name="inc_prioridad" readonly>
                                            
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="folio">Folio</label>
                                            <input type="text" class="form-control" id="inc_folio" name="inc_folio" readonly>
                                                                                
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="estacion">Estacion</label>
                                            <input type="text" class="form-control" id="inc_estacion" name="inc_estacion" readonly>
                                        </div>
                                    </div>

                                    <!-- <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Relacionar</label>
                                            <select id="relacion" name="relacion" class="form-control" required>   
                                                <option value="" selected>Selecciona</option>     
                                                <option value="1">Relacionar esta incidencia con requerimiento</option>                                             
                                                <option value="2">Agregar Requerimiento</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Requerimiento</label>
                                            <select class="form-control" id="requerimiento" name="requerimiento">
                                            </select> 
                                        </div>
                                        <div class="form-group col-md-3">
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            <br/>
                            <div class="card"> 
                                <div class="card-body">
                                    <div id="div_req" class="form-group">                                            
                                        <div class="form-row">    
                                            <div class="form-group col-md-3">
                                                <label>Tipo Solicitud</label>
                                                <input type="text" class="form-control" id="tipo_solicitud" name="tipo_solicitud" value="requerimiento" readonly>
                                            </div>                      
                                            <div class="form-group col-md-3">
                                                <label for="prioridad">Prioridad</label>
                                                <input type="text" class="form-control" id="prioridad" name="prioridad" readonly>
                                            </div> 
                                            <div class="form-group col-md-3">
                                                <label>Folio</label>
                                                <input type="text" class="form-control" id="folio" name="folio" readonly>
                                                @if ($errors->has('folio'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('folio') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <label>Estatus</label>
                                                <input type="text" class="form-control" id="estatus_incidencia" name="estatus_incidencia" readonly>
                                            </div>      
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>Area Estacion</label>
                                                <select id="id_area_estacion" name="id_area_estacion" class="form-control">                                            
                                                </select>
                                            </div>                                    
                                            <div class="form-group col-md-4">
                                                <label>Equipo/SubArea</label>
                                                <select id="id_equipo" name="id_equipo" class="form-control">                                            
                                                </select>                                                                        
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Catálogo de Refacciones</label>
                                                <select id="id_catalogo" name="id_catalogo" class="form-control">                                            
                                                </select>                                        
                                                <!-- <input type="text" id="aux_asunto" name="aux_asunto" hidden/> -->
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label>Refaccion</label>
                                                <select id="refacciones" name="refacciones" class="form-control">
                                                </select>
                                            </div>  
                                            <div class="col-md-4">
                                                <label for="cant">Cantidad</label>
                                                <select id="cant" name="cant" class="form-control">
                                                    <option value="1 Pieza">1 Pieza</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Posicion</label>
                                                <select id="posicion" name="posicion" class="form-control">
                                                </select>
                                            </div> 
                                        </div> 
                                        <br/>
                                        <div class="form-group form-row">   
                                            <div class="col-md-6">
                                                <label for="foto_ruta">Selecciona foto</label>
                                                <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_ruta" name="foto_ruta">                                                    
                                            </div> 
                                            <div class="col-md-6">
                                                <label style="color:white;">Selecciona </label>
                                                <div class="form-group form-row">
                                                <div class="ml-auto">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Agregar Requerimiento</button>                                    
                                                </div></div>
                                            </div>
                                        </div>    
                                    </div>  
                                </div>    
                            </div>   
                             <!--<div class="form-group form-row">
                                
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Actualizar Incidencia</button>                                    
                                </div> 
                            </div>    -->
                            {!!Form::close()!!}          
                        </div>            
                    </div>        
                </div>
            
            </div><!--Fin Modal Body-->            
            
        </div>
    </div>
</div>


