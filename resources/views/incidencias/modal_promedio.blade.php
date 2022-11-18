<div class="modal fade" id="modal_promedio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calcular Promedio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group form-row">
                    <div class="col-md-12">
                        <label>Selecciona un rango:</label>
                    </div>                    
                </div>

                <div class="form-group form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_desde">Fecha Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" required>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="estatus">Fecha Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
                    </div>
                </div>

                <div class="form-group form-row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                <thead class="table table-bordered">
                                    <th>Producto</th>
                                    <th>Venta</th>
                                </thead>
                                <tr>
                                    <td>Magna</td>
                                    <td>{{$promedio_magna}}</td>
                                </tr>
                                <tr>
                                    <td>Premium</td>
                                    <td>{{$promedio_premium}}</td>
                                </tr>
                                <tr>
                                    <td>Diesel</td>
                                    <td>{{$promedio_diesel}}</td>
                                </tr>
                                <tr>
                                    <td><b>Venta Total</b></td>
                                    <td><b>{{$total_venta}}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="calcular"><i class="fas fa-calculator fa-1x"></i>&nbsp;Calcular</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cerrar</button>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
     
    $("#calcular").click(function(){
        var x = document.getElementById("fecha_desde").value;
        var y = document.getElementById("fecha_hasta").value;
    });
</script>
@endsection