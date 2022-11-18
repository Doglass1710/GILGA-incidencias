@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-12">                
                <div class="card">
                    <div class="card-header"><h5>Bitácora</h5></div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@bitacora_consultas_buscar','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf

                        
                    
                        <!-- <table id="pdf" class="table table-striped table-bordered table-condensed table-hover">
                        <thead class="thead-dark">                                            
                            <th>Bitácora</th></thead>
                        <tr>
                        <td> -->
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
                                <select id="vehiculo" name="vehiculo" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                </select>                     
                            </div>
                            <div class="form-group col-md-4">
                                <label>Bitácora por Fecha:</label>
                                <select id="fecha" name="fecha" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                </select>
                            </div> 
                        </div>
                        

                        <div id="moto_inicio" class="form-group form-row">
                            <!-- <img src="images/hcargo125_2014.png" /> -->
                        </div>
                        
                        <div class="form-group form-row">
                            <div class="ml-auto">
                                <button type="reset" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                <!-- <button type="button" class="btn btn-success"><i class="fas fa-file-excel fa-1x"></i>&nbsp;Excel</button>  -->
                                <button type="button" class="btn btn-warning" onclick="printDiv('imprimir')"><i class="fas fa-print fa-1x"></i>&nbsp;Imprimir</button>  
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search fa-1x"></i>&nbsp;Buscar</button>
                                
                            </div>
                        </div> 

                        <a href="javascript:pruebaDivAPdf()" class="button">Pasar a PDF</a>

                        <div id="imprimir">

                        @foreach($bitacora as $bit)
                        
                        <center><h1>BITÁCORA</h1></center>
                        <br/>
                        <table style="width:100%;" border=0>
                            <tr>
                                <td>
                                    <h2><b>Estacion:</b> {{ $estacion }}<b>&nbsp&nbsp&nbsp Fecha de Bitácora:</b> {{ $bit->fecha_bitacora }}</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h2><b>Vehículo: </b>{{ $vehiculo }} </h2> 
                                </td>
                            </tr>
                            <tr>                        
                                <td style="text-align:center">
                                    <img src="{{ $img }}" />
                                </td>
                            </tr>
                        </table>

                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                                <h2>Nota</h2>                               
                                <h5>{{$bit->nota}}</h5>
                            </div>                                                                        

                        </div> 
                        

                        <h2>Refacciones</h2> 

                        <table style="width:100%;" border=1>
                            <tr>                                            
                                <td>Cantidad</td>
                                <td>Unidad</td>
                                <td>Refaccion</td>
                                <td>Precio Unitario</td>
                                <td>Importe (P.U. x cantidad)</td>
                                <td>IVA x Cantidad</td>
                                <td>Total (Importe + IVA)</td>                                            
                            </tr>
                            @foreach ($detalle as $ref)
                            <tr> 
                                <td>{{ $ref->cantidad }}</td>                        
                                <td>{{ $ref->unidad  }}</td>
                                <td>{{ $ref->descripcion }}</td>
                                <td>{{ $ref->importe }}</td>
                                <td>{{ $ref->puXcant }}</td>
                                <td>{{ $ref->iva }}</td>
                                <td>{{ $ref->total }}</td>
                            </tr>
                            @endforeach

                        </table>
                        <br/>
                        
                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                                <h2>Trabajo</h2>
                                <h5>{{$bit->trabajo}}</h5>
                            </div>                                                                        

                        </div>
                        <div class="form-group form-row">                                                                                        
                            
                            <div class="form-group col-md-12">
                                <h2>Observaciones</h2>
                                <h5>{{$bit->observaciones}}</h5>
                            </div>                                                                        

                        </div>
                        
                        @endforeach
                        </div>
                        {!!Form::close()!!}
                    </div>                    
                </div>                
            </div>   
        </div>        
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {


    $('#sucursal').change(event => {
        var estacion=$('select[name=sucursal]').val();       
        var moto=''; 
        var fecha='';

        $('#vehiculo').empty();
        $('#fecha').empty();
        $.get('{{ url("/") }}/moto',{estacion: estacion},function(data){
                $('#vehiculo').append('<option value="">Selecciona...</option>')
            $.each(data,function(fetch, miobj){
                $('#vehiculo').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');  
                moto= $('select[name=vehiculo]').html();
            });
        });        
    });

    $('#vehiculo').change(event => {
        var estacion=$('select[name=sucursal]').val();       
        var moto=$('select[name=vehiculo]').val();  
        var fecha='';
        $.get('{{ url("/") }}/fecha',{estacion: estacion, vehiculo: moto},function(data){
                $('#fecha').empty();
            $.each(data,function(fetch, miobj){
                $('#fecha').append('<option value="' + miobj.id + '">' + miobj.fecha + '</option>');  
                fecha=$('#fecha').text();    
            });
        });
    });
});

// function boton(){

//     if($("#moto_inicio").css("display")=="block")
//     {
//         $("#moto_inicio").css("display","none");
//     }
    
// //   var estacion=$('select[name=sucursal]').val();
// //   var moto=$('select[name=vehiculo]').find('option:selected').text();
// //   var fecha=$('select[name=fecha]').html();
// //   $('#info').html(estacion + ', ' + moto + ', ' + fecha);
// }

function printDiv(nombreDiv) {
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;
     document.body.innerHTML = contenido;
     window.print();
     document.body.innerHTML = contenidoOriginal;
}


function pruebaDivAPdf() {
        var pdf = new jsPDF('p', 'pt', 'letter');
        source = $('#imprimir')[0];

        specialElementHandlers = {
            '#bypassme': function (element, renderer) {
                return true
            }
        };
        margins = {
            top: 40,
            bottom: 40,
            left: 40,
            width: 550
        };

        pdf.fromHTML(
            source, 
            margins.left, // x coord
            margins.top, { // y coord
                'width': margins.width, 
                'elementHandlers': specialElementHandlers
            },

            function (dispose) {
                pdf.save('Prueba.pdf');
            }, margins
        );
    }


</script>
@endsection