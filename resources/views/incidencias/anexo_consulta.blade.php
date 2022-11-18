@extends('layouts.app')

@section('content')


<div class="container-fluid">
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    {!!Form::open(array('action'=>'IncidenciasController@anexo_consulta','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
    {{Form::token()}}
    @csrf

    <div class="row">

        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-1">
            <h3>ANEXO</h3>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-5">
            <h4><label id="lbl_suc">{{$aux_sucursal}} &nbsp; </label><label id="lbl_mes">{{$aux_mes}}</label></h4>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-2">
            <!-- <h3>{{ $sucursal }}</h3> -->
            <select id="estacion" name="estacion" class="form-control">
                <option value="">Selecciona...</option>
            @foreach($sucursal as $suc){
                <option value="{{ $suc->estacion }}">{{ $suc->sucursal }}</option>
            }
            @endforeach
            </select>
            <input type="text" id="aux_sucursal" name="aux_sucursal" value="{{$aux_sucursal}}" hidden/>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-2">
            <select id="mes" name="mes" class="form-control">
            @foreach($meses as $mes){
                <option value="{{ $mes->MES_NUM }}">{{ $mes->MES_LETRA }}</option>
            }
            @endforeach
            </select>
            <input type="text" id="aux_mes" name="aux_mes" value="{{$aux_mes}}" hidden/>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xl-2">
            @if($id_usuario_permiso=="53")
            <button type="submit" class="btn btn-primary" id="btn_buscar"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>
            <button type="button" id="delete_record" class="btn btn-danger"><i class="fas fa-window-close fa-1x"></i>&nbsp;Borrar</button>
            @endif
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 col-md-auto col-lg-auto col-sm-auto col-xl-auto">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">                    
                    <thead class="thead-dark">
                        <!-- <th><i class="fas fa-gas-pump fa-1x"></i>Estacion</th> -->
                        <!-- <th class="sorting_asc" aria-label="Dia: activate to sort column descending" aria-sort="ascending">Dia</th> -->
                        <th>Dia</th>
                        <th>Producto</th>
                        <th>I. Físico Inicial</th>
                        <th>Compras</th>
                        <th>Ventas</th>
                        <th>Ventas Acum.</th>
                        <th>I. Teórico</th>
                        <th>I. Físico Final</th>
                        <th>Variación</th>
                        <th>Acum.</th>
                        <th>% Variación</th>
                        <th>AC.%</th>
                        <th>Rot Inv.</th>
                        <th></th>
                    </thead>
                    
                    @foreach($consulta as $c)
                    <tr> 
                        <!-- <td>{{ $c->estacion }}</td> -->
                        <td>{{ $c->dia }}</td>
                        <td>{{ $c->producto }}</td>
                        <td>{{ $c->inv_inicial }}</td>
                        <td>{{ $c->compra }}</td>
                        <td>{{ $c->venta }}</td>
                        <td>{{ $c->venta_acum }}</td>
                        <td>{{ $c->inv_teorico }}</td>
                        <td>{{ $c->inv_final }}</td>
                        <td>{{ $c->variacion }}</td>
                        <td>{{ $c->Acum }}</td>
                        <td>{{ $c->porcen_variacion }} %</td>
                        <td>{{ $c->ac }} %</td>
                        <td>{{ $c->rot_inv }}</td>
                        <td><input type='checkbox' class='delete_check' id='delcheck_".{{ $c->id }}."' onclick='checkcheckbox();' value='".{{ $c->id }}."'></td>
                    </tr>
                    @endforeach
                   
                </table>

            </div>

            
        </div>

    </div>
    {!!Form::close()!!}
    
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
                
        dataTable = $('#myTable').DataTable({
            "language": {
            "sProcessing":     "Procesando...",
            "lengthMenu": "Mostrar _MENU_ filas",
            "zeroRecords": "No hay coincidencias",
            "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ total de registros)",
            "sSearch":         "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
        }
        });

    // Check all 
    $('#checkall').click(function(){
        if($(this).is(':checked')){
            $('.delete_check').prop('checked', true);
        }else{
            $('.delete_check').prop('checked', false);
        }
    });

    // Delete record
   $('#delete_record').click(function(){

        var deleteids_arr = [];
        // Read all checked checkboxes
        $("input:checkbox[class=delete_check]:checked").each(function () {
        alert(deleteids_arr.push($(this).val()) );
        });

        // Check checkbox checked or not
        if(deleteids_arr.length > 0){

        // Confirm alert
        var confirmdelete = confirm("Do you really want to Delete records? ");
        if (confirmdelete == true) {
            $.ajax({
                url: 'ajaxfile.php',
                type: 'post',
                data: {request: 2,deleteids_arr: deleteids_arr},
                success: function(response){
                    dataTable.ajax.reload();
                }
            });
        } 
        }
        });
        // Checkbox checked
        function checkcheckbox(){

        // Total checkboxes
        var length = $('.delete_check').length;

        // Total checked checkboxes
        var totalchecked = 0;
        $('.delete_check').each(function(){
            if($(this).is(':checked')){
                totalchecked+=1;
            }
        });

        // Checked unchecked checkbox
            if(totalchecked == length){
                $("#checkall").prop('checked', true);
            }else{
                $('#checkall').prop('checked', false);
            }
        }


        $('#btn_buscar').click(function() {
        var sucursal = $('select[name=estacion]').find('option:selected').text();
        var mes = $('select[name=mes]').find('option:selected').text();
        $("#aux_sucursal").val(sucursal);
        $("#aux_mes").val(mes);
        });


    });   
       
</script>
@endsection