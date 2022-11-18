
<div class="modal fade" id="modal_cerrar_compra" tabindex="" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Realmente desea cerrar la compra?</h5>
                <i class="far fa-question-circle fa-2x"></i>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&#10004;</span>
                </button>-->
            </div>
            
            <div class="modal-body">
                <p>Al cerrar la compra ya no se podra hacer ninguna modificación</p>                   
                                    
                <form method="POST" action="{{ route('cerrar_compra') }}" enctype="multipart/form-data">
                    @csrf                                                        
                    <input type="hidden" name="cerrar_id_incidencia" id="cerrar_id_incidencia" value="">
                    <input type="hidden" name="cerrar_id_compra" id="cerrar_id_compra" value="">
                    <div class="form-group form-row">
                        <div class="form-group col-md-6">
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;No</button>
                            
                            
                        </div>
                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-check fa-1x"></i>&nbsp;Si</button>
                        </div>
                    </div>                        
                </form>      
            
            </div><!--Fin Modal Body-->            
            
        </div>
    </div>
</div>

