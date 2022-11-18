
<div class="modal fade" id="modal_comentarios" tabindex="" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-sm" role="document">
        <div class="modal-content">            
          
        {!!Form::open(array('action'=>'IncidenciasController@insertar_comentarios','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
            {{Form::token()}}
                @csrf           
            @csrf
            <div class="modal-body">
                <br/>
                <div >
                    <div class="row" id="groupbox">
                        <div class="form-group col-md-10">
                            <div class="form-group">
                                <!-- <label>Comentarios</label> -->
                                <input type="text" class="form-control" id="txt_observaciones" name="txt_observaciones" placeholder="Comentario..." required>
                                <input type="text" id="id" name="id" style="border:0px" hidden/>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <div class="form-group">
                            @if($role =="admin")
                            <button type="submit" id="btn_guardar" class="btn btn-warning"><i class="fas fa-plus-square"></i>&nbsp;Agregar</button>
                            @endif
                            </div>
                        </div>
                    </div>
                </div> 

                    <div class="table-responsive">
                        <table id="tbl_comentarios" class="table table-hover" style="font-size: 13px;">

                            <caption>Comentarios a la incidencia 
                                <input id="id" name="id" style="border:0px" disabled/> 
                            </caption> 
                            <tbody>
                            </tbody>         
                        </table>
                                                                
                    </div>
                
            </div>
            {!!Form::close()!!}     
            
        </div>
    </div>
</div>


