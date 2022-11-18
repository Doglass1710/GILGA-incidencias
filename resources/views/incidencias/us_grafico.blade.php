@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          Incidencias pendientes por Usuario
        </div>
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
              
              function drawChart() 
              {
                var data = google.visualization.arrayToDataTable([
                  ['Estacion', 'Cantidad'],
                  @foreach($incidencias as $inci)
                    ['{{$inci->estacion}}-{{$inci->nombre_corto}}, ({{$inci->total}})',{{$inci->total}}],
                  @endforeach
                ]);

                var options = {
                  title: 'Incidencias por Estacion, del usuario: '+'{{ $nombre }}' , 
                  
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
                      //if({{ $nombre }} <> "Lic. Gilberto")
                      //{
                        location.href="incidencias/estacion/"+estacion+"/us_grafico";
                      //}                    
                      
                    }
                }
                  // Listen for the 'select' event, and call my function selectHandler() when
                  // the user selects something on the chart.
                  if('{{ $nombre }}' == 'Admin' || '{{ $nombre }}' == 'Ana')
                  {

                  }else{
                    google.visualization.events.addListener(chart, 'select', selectHandler);
                  }

                chart.draw(data, options);
              }      
            </script>
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                  var data = google.visualization.arrayToDataTable([
                    ['Task', 'Incidencias'],
                    @foreach($areas_atencion as $art)
                      ['{{$art->descripcion}}',{{$art->total}}],
                    @endforeach
                  ]);

                  var options = {
                    title: 'Areas por Estacion, del usuario: '+'{{ $nombre }}' 
                  };

                  var chart = new google.visualization.BarChart(document.getElementById('grafico2'));

                  chart.draw(data, options);
                }
            </script>
            <div id="piechart" style="height: 500px;"></div>
            <div id="grafico2" style="height: 500px;"></div>
        </div> 
      </div> 
    </div> 
  </div>    
</div>

@endsection
