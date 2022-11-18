@extends('layouts.app')

@section('content')
    <div class="container">        
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
        <div class="row justify-content-center">            
            <div class="col-md-12">   
                
                <div class="card">
                    <div class="card-header">Reporte Anexo Grafica de Ventas</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@ReporteAnexoGraficas','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="form-group form-row">
                                <div class="form-group col-md-4">
                                <label>Estacion: </label>
                                    <select id="estacion" name="estacion" class="form-control" >
                                    <option value="">Selecciona una estación...</option>
                                        @foreach($estaciones as $es)
                                        <option value="{{$es->estacion}}">{{$es->sucursal}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="aux_sucursal" name="aux_sucursal" hidden>
                                </div>   

                                <div class="form-group col-md-4">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" name="fecha_desde" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta" required>
                                </div>
                            </div>   
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Gráfico</button>                                    
                                </div>
                            </div>
						
                        </div>
                    </div>
                    <div class="dropdown-divider"></div><br/>

                        {!!Form::close()!!}

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>     
                    
                    <script type="text/javascript">
                    google.charts.load('current', {packages: ['corechart', 'bar']});
                    google.charts.setOnLoadCallback(drawBasic);

                    function drawBasic() {

                        var data = google.visualization.arrayToDataTable([
                        ['Element', '', { role: 'style' }],
                        @foreach($porestacion as $est)
                        ['{{$est->gasolina}}', {{$est->venta}}, '#{{$est->color}}'],
                        @endforeach
                        ]);

                        var view = new google.visualization.DataView(data);
                        view.setColumns([0, 1,
                                        { calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation" },
                                        2]);

                        var options = {
                            title: 'Rango del {{$fecha1}} al {{$fecha2}}. Venta Total: {{$total_venta}}' ,
                            legend: { position: "none" },
                            hAxis: {
                            title: 'Estacion: {{$sucursal}}' 
                            },
                            vAxis: {
                            title: 'Litros'
                            }
                        };

                        var chart = new google.visualization.ColumnChart(
                            document.getElementById('chart_div'));

                        chart.draw(view, options);
                        }
                        </script>               




                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart1);

                        function drawChart1() {

                            var data = google.visualization.arrayToDataTable([
                            ['Estacion', 'Ventas'],
                            @foreach($datos as $dt)
                                ['{{$dt->estacion}} - {{$dt->nombre_corto}}',{{$dt->Venta}}],
                            @endforeach
                            ]);

                            var options = {
                            title: 'Grafico de Ventas en general del día: {{$fecha1}} al {{$fecha2}}',
                            };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                        
                        //google.visualization.events.addListener(chart, 'select', selectHandler);
                        chart.draw(data, options);
                      }                   
                      
                    </script>


                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                            ['Estación', 'Magna', 'Premium', 'Diesel'],
                            @foreach($producto as $pro)
                                ['{{$pro->estacion}}',{{$pro->Magna}},{{$pro->Premium}},{{$pro->Diesel}}],
                            @endforeach
                            ]);

                            var options = {
                            chart: {
                                title: 'Ventas de combustible por estación (Litros)',
                                subtitle: 'Del {{$fecha1}} al {{$fecha2}}',
                                legend:'left'
                            },
                            vAxis: {format: 'decimal'},
                            colors: ['#1b9e77','#d50000','#202124']
                            };

                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                    <div class="form-group form-row">
                        <div class="col-md-8">
                            <center><div id="chart_div" style="width: 100%; height: 400px;"></div></center>
                        </div>
                        <div class="col-md-3">
                        
                            <div class="form-row">
                                <input type="text" id="estacion2" value="{{$estacion}}" hidden/></pre>
                                <div class="form-group col-md-6">
                                    <label for="fecha_desde">Fecha Desde</label>
                                    <input type="date" class="form-control" id="fecha_1" name="fecha_1" required>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="estatus">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha_2" name="fecha_2" required>
                                </div>
                            </div>
                            <div class="table-responsive" style="padding-top:0; padding-bottom:0;">
                                <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover">
                                    <thead class="table table-bordered">
                                         <th colspan=2" style="text-align:center">Promedio de Venta</th>
                                        <!--<th>Promedio de Venta</th>
                                        <th>
                                        </th> -->
                                    </thead>
                                    <tr>
                                        <td>Magna</td>
                                        <td><label id="promedio_magna"></label></td>
                                    </tr>
                                    <tr>
                                        <td>Premium</td>
                                        <td><label id="promedio_premium"></label></td>
                                    </tr>
                                    <tr>
                                        <td>Diesel</td>
                                        <td><label id="promedio_diesel"></label></td>
                                    </tr>
                                    <!-- <tr>
                                        <td><b>Venta Total</b></td>
                                        <td><b><label id="total_venta"></label></b></td>
                                    </tr> -->
                                </table>
                                        <button type="submit" class="btn btn-success" id="calcular">
                                        <i class="fas fa-calculator fa-1x"></i>&nbsp;Calcular</button>
                                    
                            </div>
                           
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>

                    <div id="piechart" style="width: 1000px; height: 500px;"></div>
                    <div id="columnchart_material" style="width: 100%; height: 500px;"></div>

                    </div>  
                    <div class="card-footer"> </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection


@section('script')
<script>
     
 $(document).ready(function() {
    $('#estacion').change(event => {
        var sucursal = $('select[name=estacion]').find('option:selected').text();
        $("#aux_sucursal").val(sucursal);
    });

        
    
    $("#calcular").click(function(){
        var fecha1 = document.getElementById("fecha_1").value;
        var fecha2 = document.getElementById("fecha_2").value;
        var estacion =document.getElementById("estacion2").value;

        if(fecha1 == "" || fecha2 == ""){
            alert("Selecciona un rango");
            $('#promedio_magna').html("0");
            $('#promedio_premium').html("0");
            $('#promedio_diesel').html("0");
        }else 
        if(fecha1>fecha2){
            alert("La fecha de inicio debe ser menor o igual a la fecha final");
            $('#promedio_magna').html("0");
            $('#promedio_premium').html("0");
            $('#promedio_diesel').html("0");
        }else
        {
            $.get('{{ url("/") }}/calcularPromedio',{fecha1: fecha1, fecha2: fecha2, estacion: estacion},function(data){
                $('#promedio_magna').html("0");
                $('#promedio_premium').html("0");
                $('#promedio_diesel').html("0");

                $('#promedio_magna').html(data.promedio_magna); 
                $('#promedio_premium').html(data.promedio_premium); 
                $('#promedio_diesel').html(data.promedio_diesel);   
            }); 
        }   
    });
});
</script>
@endsection