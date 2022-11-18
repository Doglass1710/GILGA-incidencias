<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Bitácora de Dispensarios</title>
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

        <style>
        .table th,
        .table td {
            padding: 0.4rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        img {
            width:100%;
            height:410px;
        }
        .page-break {
            page-break-after: always;
        }
        </style>
    </head>
    <body style="background-color:white;">

        <header>
            <table style="width:100%;">
                <tr>
                    <td style="width:33%;">
                        <img src="{{ asset('images/logo_gilga.png') }}" alt="Logo" style="height:61px; width:120px"/>
                    </td>
                    <td style="width:66%;">
                        <h5>Bitácora de Dispensarios</h5>
                    </td>
                </tr> 
                <tr><td colspan="2" style="text-align:center;">
                    <h6>{{ $sucursal }}. De {{ $fecha1 }} a {{ $fecha2 }}</h6>
                    </td>
                </tr>
            </table>
        </header>

        <footer style="font-size:5px;">          
            <script type='text/php'>
                if (isset($pdf)) 
                {              
                    $pdf->page_text(60, $pdf->get_height() - 50, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
                }
            </script>
        </footer>

        <main>
            <!-- <div class="container" style="background-color:white;">
                <div class="row justify-content-center" style="padding-top: 25px;">    
                    <div class="col-md-12">  -->

                        <table id="myTable" class="table  table-bordered" style="font-size:10px;width:100%;">
                            <tr>        
                                @if($sucursal=='Sucursales') 
                                    <th>Estación</th>
                                @endif                           
                                <th>Fecha</th>                          
                                <th>Hora</th>                             
                                <th>Descripción</th>   
                                <!-- <th>Folio</th>               -->
                                <th>Ajuste</th>         
                            </tr>
                            @foreach ($consulta as $bit)
                            <tr>
                                @if($sucursal=='Sucursales') 
                                    <td style="padding: 0.2rem;width:10%">{{ $bit->estacion }}</td>
                                @endif
                                <td style="padding: 0.2rem;width:10%">{{ $bit->fecha }}</td>
                                <td style="padding: 0.2rem;width:10%">{{ $bit->hora }}</td>
                                <td style="padding: 0.2rem;">{{ $bit->descripcion }}</td>
                                <!-- <td style="padding: 0.2rem;width:10%">{{ $bit->acuse }}</td>  -->
                                <td style="padding: 0.2rem;width:20%">{{ $bit->factor_ajuste }}</td> 
                            </tr>    
                            @endforeach            
                        </table>                            

                    <!-- </div>
                </div> 
            </div> -->
        </main>
    </body>
</html>