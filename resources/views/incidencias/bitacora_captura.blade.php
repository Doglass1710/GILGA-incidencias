@extends('layouts.app')

@section('content')
    <div class="container">   
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif     
        <div class="row justify-content-center">            
            <div class="col-md-10">                
                <div class="card">
                    <div class="card-header"><h5>Bitácora</h5></div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@capturar_bitacora','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}                        
                        @csrf
                        <div class="form-group form-row">
                            <div class="form-group col-md-4">
                                <label>Sucursal</label>
                                <select id="sucursal" name="sucursal" class="form-control" required>
                                <option value="">Selecciona una sucursal...</option>
                                    @foreach($sucursal as $est)
                                        <option value="{{$est->estacion}}">{{$est->sucursal}}</option>
                                    @endforeach 
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Vehículo</label>      
                                <select id="vehiculo" name="vehiculo" class="form-control">
                                    <option value="0">Selecciona...</option>
                                </select>                     
                            </div>

                            <div class="form-group col-md-4">
                                <label name="fecha">Fecha:</label>
                                <input type="date" name="fecha" class="form-control" required/>
                            </div> 
                        </div>
                        <!-- <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-5">
                            </div>
                            <div class="form-group col-md-1">
                                <label name="fecha">Fecha:</label>
                            </div>
                            <div class="form-group col-md-6">
                                
                                <input type="date" name="fecha" class="form-control" required/>
                            </div>                                                                        

                        </div> -->
                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                                <label>Nota</label>
                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="nota" name="nota" maxlength="5000" required></textarea>
                            </div>                                                                        

                        </div>                            
                                    
                    <!--Aqui empieza el CARD para los detalles de la compra -->
                        <div class="card"><div class="card-header"><b>Refacciones</b></div>
                            <div class="card-body">
                                <div class="form-group form-row">
                                    
                                    <div class="form-group col-md-6">
                                        <label for="refaccion">Refacción</label>
                                        <select id="pieza" name="pieza" class="form-control">
                                                @foreach($catalogo as $piezas)
                                                    <option value="{{$piezas->id}}">{{$piezas->descripcion}}</option>
                                                @endforeach 
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="pcantidad">Cantidad</label>
                                        <input type="number" class="form-control" id="pcantidad" name="pcantidad" min="1">
                                    </div>
                                    
                                    <div class="form-group col-md-2">
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
                                        <!-- <input type="number" class="form-control" id="pprecio_unitario" name="pprecio_unitario" min="0"> -->
                                        <input type="text" class="form-control" id="pprecio_unitario" name="pprecio_unitario">
                                    </div>              

                                </div>
                                <div class="form-group form-row">
                                    <div class="ml-auto">                                                        
                                        <button type="button" class="btn btn-primary" id="btn_add">
                                        <i class="fas fa-plus-square fa-1x"></i>&nbsp;Agregar</button>                                        
                                    </div>
                                </div>

                                <div class="form-group form-row">
                                    <div class="form-group col-lg-12">
                                        
                                        <div class="table-responsive">
                                            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                                <thead class="thead-dark">  
                                                    <th>ID_Ref.</th>                                                  
                                                    <th>Refacción_detalle</th>
                                                    <th>Cantidad</th>
                                                    <th>Unidad</th>
                                                    <th>Precio_Unitario</th>
                                                    <th>Impuesto_IVA</th>
                                                    <th>Total_(PrecioUnitario+IVA)</th>               
                                                    <th> </th>                                          
                                                </thead>
                                                    
                                                <tbody>
                                                </tbody>
                                                
                                                <tfoot>                                                                    
                                                    <th></th>    
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>                                                              
                                                    <th><input type="text" class="form-control" id="subtotal" name="subtotal" readonly></th>
                                                    <th><input type="text" class="form-control" id="iva" name="iva" readonly></th>
                                                    <th><input type="text" class="form-control" id="total_final" name="total_final" readonly></th>
                                                    <th></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>     
                        <br/>
                        
                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                            <label>Trabajo</label>
                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="trabajo" name="trabajo" maxlength="5000" required></textarea>
                            </div>                                                                        

                        </div>
                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="observaciones" name="observaciones" maxlength="5000"></textarea>
                            </div>                                                                        

                        </div>            

                        <div id="guardar">
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit fa-1x"></i>&nbsp;Guardar</button>
                                                
                                </div>
                            </div> 
                        </div>        
                    </div>                    
                </div>                
            </div>   
        </div>        
    </div>
@endsection

@section('script')
<script>

    
    var total = [];
    var cont = 0;
    var total_final = 0;
    var subtotal = 0;
    var iva = 0;

$(document).ready(function() {

    $('#sucursal').change(event => {
        var estacion=$('select[name=sucursal]').val();

        $('#vehiculo').empty();
        $.get('{{ url("/") }}/moto',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
                $('#vehiculo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');                
            });
        });
    });


    $('#btn_add').click(function() {

        var estacion=$('select[name=sucursal]').val();
        var desc_catalogo = $('select[name=pieza]').find('option:selected').text();
        var id_catalogo = $('select[name=pieza]').val();
        var precio_unitario = parseFloat($("#pprecio_unitario").val());
        var unidad = $("#punidad").val();
        var cantidad = parseFloat($("#pcantidad").val());
        total[cont] = precio_unitario * cantidad; 

        
        //Debo validar que Cantidad,precio_unitario y tipo_cambio sean numeros
        var pcantidad = parseFloat(document.querySelector("#pcantidad").value);
        var pprecio_unitario = parseFloat(document.querySelector("#pprecio_unitario").value);

        if(isNaN(pcantidad) || pcantidad == null){
            alert("Introduce una cantidad valida");
            return false;
        }

        if(isNaN(pprecio_unitario) || pprecio_unitario == null){
            alert("Introduce un precio unitario valido");
            return false;
        }

        if(id_catalogo!="" && unidad!=""){
            subtotal = parseFloat(subtotal) + parseFloat(total[cont]);
            var iva_tabla = (parseFloat(total[cont]))* 0.16;
            iva = subtotal * 0.16;
            total_final = subtotal + iva;


            console.log(subtotal,iva,total_final);
            
            var fila = '<tr class="selected" id="fila'+cont+
            '"><td><input type="text" class="form-control" name="refaccion[]" value="'+id_catalogo+
            '" readonly></td><td>'+desc_catalogo+
            '</td><td><input type="text" class="form-control" name="cantidad[]" value="'+cantidad+
            '" readonly></td><td><input type="text" class="form-control" name="unidad[]" value="'+unidad+
            '" readonly></td><td><input type="text" class="form-control" name="precio_uni[]" value="'+pprecio_unitario+
            '" readonly></td><td><input type="text" class="form-control" name="iva_uni[]" value="'+iva_tabla+
            '" readonly></td><td><input type="text" class="form-control" name="total_uni[]" value="'+(total[cont]+iva_tabla)+
            '" readonly></td><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+
            ');"><i class="far fa-trash-alt fa-1x"></i></button></td></tr>';
            cont++;
            //limpiar();

            subtotal = subtotal.toFixed(2);
            iva = iva.toFixed(2);
            total_final = total_final.toFixed(2);

            $("#subtotal").val(subtotal);
            $("#iva").val(iva);
            $("#total_final").val(total_final);
            //evaluar();
            
            $('#detalles').append(fila);

            //debo borrar el value de id_incidencia_detalle donde el value sea el id_incidencia
            var selectobject = document.getElementById("id_catalogo");
            for (var i=0; i<selectobject.length; i++) {
                if (selectobject.options[i].value == id_catalogo)
                    selectobject.remove(i);
            }
        
        }else{
            alert("Verifique los detalles de la compra");
        }        

    });

});

    function eliminar(index){
        subtotal = subtotal - total[index];
        iva = subtotal * 0.16;
        total_final = subtotal + iva;
        $("#subtotal").val(subtotal);
        $("#iva").val(iva);
        $("#total_final").val(total_final);

        $("#fila"+index).remove();
        //evaluar();
    }


    $(function(){

        $('#pprecio_unitario').keypress(function(e) {
        if(isNaN(this.value + String.fromCharCode(e.charCode))) 
            return false;
        })
        .on("cut copy paste",function(e){
            e.preventDefault();
        });

    });

</script>
@endsection