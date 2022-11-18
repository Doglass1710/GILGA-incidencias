
<div class="modal fade" id="refaccionesmodal" tabindex="" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-sm" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">                
                
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
                            <div class="table-responsive">
                                <table id="refacciones_table" class="table table-light  table-bordered table-condensed table-hover">
                                    <thead class="thead-dark">
                                        <th>ID Refac.</th>                       
                                        <th>Descripcion</th>                   
                                        <th>Catálogo     </th>
                                        <th>Prioridad</th>
                                    </thead>
                                    @foreach ($refacciones_estacion as $ref_est)
                                    <tr>
                                        <td>{{ $ref_est->id }}</td>
                                        <td>{{ $ref_est->descripcion }}</td> 
                                        <td>{{ $ref_est->catalogo }}</td>                                         
                                        <td>{{ $ref_est->prioridad }}</td>
                                    </tr>
                                    @endforeach
                                    
                                </table>

                            </div>


                        </div>

                    </div>
                </div>
            
            </div><!--Fin Modal Body-->            
            
        </div>
    </div>
</div>


