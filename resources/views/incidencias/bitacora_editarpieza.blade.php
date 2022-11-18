<div class="modal fade" id="bitacora_editarpieza" tabindex="" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Editar la descripción de Refacciones</h5>
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
                                    
                                    <form method="POST" action="{{ route('editar') }}"  enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group form-row">                                
                                                                       
                                            <div class="form-group col-md-2">
                                                <label>Descripción</label>
                                                <input type="text" id="id" name="id" hidden/>
                                                <input type="text" name="editar" value="editar" hidden/>
                                            </div>                                       
                                            <div class="form-group col-md-10">
                                                <textarea style="overflow:auto;resize:none" class="form-control" id="descripcion" name="descripcion" required></textarea>
                                            </div>
                                        </div>                                     
                                        
                                        <div class="form-group form-row">
                                            <div class="ml-auto">
                                                <button type="reset" class="btn btn-secondary" data-dismiss="modal" ><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Editar</button>                                    
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

