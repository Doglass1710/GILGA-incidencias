@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Bienvenido a GILGAAdmin Web 1.0</div>
                
                @if(\Auth::user()->role == 'admin')
                    <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif                   
                    
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load('current', {'packages':['corechart']});
                      google.charts.setOnLoadCallback(drawChart);

                      function drawChart() {

                        var data = google.visualization.arrayToDataTable([
                          ['Estacion', 'Cantidad'],
                          @foreach($incidencias as $inci)
                            ['{{$inci->estacion}}-{{$inci->nombre_corto}}',{{$inci->total}}],
                          @endforeach
                        ]);

                        var options = {
                          title: 'Incidencias por Estacion',
                          
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                        
                        function selectHandler() {
                            var selectedItem = chart.getSelection()[0];
                            if (selectedItem) {
                              var topping = data.getValue(selectedItem.row, 0);
                              //alert('The user selected ' + topping);                              
                              var estacion = topping;
                              estacion = estacion.split("-");
                              estacion = estacion[0];
                              //console.log(estacion);
                              //aqui debo llamar a un metodo que me traiga las incidencias abiertas de la estacion
                              location.href="incidencias/estacion/"+estacion;
                              
                            }
                        }
                        // Listen for the 'select' event, and call my function selectHandler() when
                        // the user selects something on the chart.
                        google.visualization.events.addListener(chart, 'select', selectHandler);


                        chart.draw(data, options);
                      }                   
                      
                    </script>
                    
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                          var data = google.visualization.arrayToDataTable([
                            ['Task', 'Incidencias'],
                            @foreach($areas_estacion as $art)
                              ['{{$art->descripcion}}',{{$art->total}}],
                            @endforeach
                          ]);

                          var options = {
                            title: 'Areas por Estacion'
                          };

                          var chart = new google.visualization.BarChart(document.getElementById('pie2chart'));

                          chart.draw(data, options);
                        }
                    </script>    
  
                    <div id="piechart" style="height: 500px;"></div>
                    <div id="pie2chart" style="height: 500px;"></div>
                    
                    </div> 
                
                    
                    
            </div> 
                @elseif(\Auth::user()->role == 'indigo')
                    <div class="card-body"></div>
                    <div style="text-align:center;height: 500px">  
                    <br/>                        
                      <img src="images/HOTEL INDIGO GRANDE.png" style="width:600px; height:300px;">
                      
                    </div> 
                  @else
                    <div class="card-body">Inicio</div>
                    <div style="text-align:center;height: 600px">   
                    <br/>
                    <br/>
                    <br/>                        
                      <img src="LogoGrupoGilga.png" class="animated fadeIn" style="width:500px; height:250px; border-radius: 20%;">
                      
                    </div>
                @endif
                               
        </div>            
    </div>        
</div>    



    



@endsection
