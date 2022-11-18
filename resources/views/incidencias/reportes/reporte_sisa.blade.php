<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte</title>
        
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
        <div class="container" style="background-color:white;">
            <div class="row justify-content-center" style="padding-top: 25px;">    
                <div class="col-md-8"> 

            <table style="width:100%;">
                <tr><td>
                    <img src="{{ asset('images/logo_gilga.png') }}" alt="Logo" style="height:61px; width:120px"/>
                    </td><td></td>
                </tr>                
                <tr><td colspan="2" style="text-align:center;">
                    <h4>{{ $razon_social }}</h4>
                    </td>
                </tr>                
                <tr><td colspan="2" style="text-align:center;">
                    <h5>{{ $sucursal }}</h5>
                    </td>
                </tr>
                <tr><td colspan="2" style="text-align:center;">
                    <label style="color:white;">PDF</label>
                </td></tr>
            </table>

        <!--<div class="contenido"> 
            <div class="table-responsive"> -->
                @foreach ($consulta as $ss)
                <table id="myTable" class="table  table-bordered table-condensed table-hover">
                    <tr>                                    
                        <th>Fecha</th>  
                        <td>{{ $ss->fecha }}</td> 
                    </tr>
                    <tr > 
                        <th>Hora</th>  
                        <td>{{ $ss->hora }}</td>                    
                    </tr>
                    <tr > 
                        <th>Gerente</th>  
                        <td>{{ $ss->gerente }}</td>                    
                    </tr>
                    <tr > 
                        <th>Operador</th>  
                        <td>{{ $ss->operador }}</td>                    
                    </tr>
                    <tr > 
                        <th>Tipo Combustible</th>  
                        <td>{{ $ss->producto }}</td>                    
                    </tr>
                    <tr > 
                        <th>Volumen</th>  
                        <td>{{ $ss->volumen }}</td>                    
                    </tr>
                    <tr > 
                        <th>Placas Unidad</th>  
                        <td>{{ $ss->placas }}</td>                    
                    </tr>
                    <tr > 
                        <th>Remisión</th>  
                        <td>{{ $ss->remision }}</td>                    
                    </tr>
                    <tr > 
                        <th>Factura</th>  
                        <td>{{ $ss->factura }}</td>                    
                    </tr>
                    <tr > 
                        <th>Volumen Inicial</th>  
                        <td>{{ $ss->volumen_inicial }}</td>                    
                    </tr>
                    <tr > 
                        <th>Volumen Final</th>  
                        <td>{{ $ss->volumen_final }}</td>                    
                    </tr>
                    <tr > 
                        <th>Aumento Bruto</th>  
                        <td>{{ $ss->aumento }}</td>                    
                    </tr>
                    <tr > 
                        <th>Vta. en descarga</th>  
                        <td>{{ $ss->venta }}</td>                    
                    </tr>
                    <tr > 
                        <th>Cubetas fin descarga (19 lts.)</th>  
                        <td>{{ $ss->cubetas }}</td>                    
                    </tr>
                </table>
                <b>Observaciones: {{ $ss->observaciones }}</b>

                <div class="page-break"></div>
                
                <table>
                    <tr><td>Foto de SISA</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_sisa) }}"  />
                        </td>
                    </tr>
                    <tr><td>Foto de sello de Domo</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_domo) }}"/>
                        </td>
                    </tr>
                </table>

                <div class="page-break"></div>
                <table>
                    <tr><td>Foto de sello de caja válvulas</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_valvulas) }}"/>
                        </td>
                    </tr>
                    <tr><td>Foto Remisión</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_remision) }} "/>
                        </td>
                    </tr>
                </table>
                
                <div class="page-break"></div>
                <table>
                    <tr><td>Foto de tanque vacío</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_tanque) }}"/>
                        </td>
                    </tr>
                    <tr><td>Foto de tira de descarga</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_tira) }}"/>
                        </td>
                    </tr>
                </table>
                
                <div class="page-break"></div>                
                <table>
                    <tr><td>Foto de sistema (Venta durante la descarga)</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_venta) }}"/>
                        </td>
                    </tr>

                @if($ss->foto_relleno==0 || $ss->foto_relleno=="" )

                    <tr><td>Foto de ticket de relleno - No aplica</td></tr>

                @else

                    <tr><td>Foto de ticket de relleno</td></tr>
                    <tr>
                        <td>
                            <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_relleno) }}"/> 
                        </td>
                    </tr>                    

                @endif

                </table>

                @if($ss->foto_cubetas>0 && $ss->foto_relleno>0 )  
                    <div class="page-break"></div>
                @endif
                
                @if($ss->foto_cubetas==0 || $ss->foto_cubetas=="" )

                    <table>                    
                        <tr><td>Foto de cubetas - No aplica</td></tr>
                    </table>

                @else

                    <table>                    
                        <tr><td>Foto de cubetas</td></tr>
                        <tr>
                            <td>
                                    <img src="{{ asset('../storage/app/img_sisa/'.$ss->foto_cubetas) }}"/>  
                            </td>
                        </tr>    
                    </table>  

                @endif

                  

                @endforeach

                <table  style="position: fixed; bottom: 1.5cm;  width:100%; text-align: center; left: 2.5cm">
                    <tr>
                        <td style="text-align:center;width:50%;">
                            <hr size="2" width="80%" align="center" color="black" style="margin-bottom: 0px; margin-top: 0px;">
                            <p style="margin-bottom: 0px;margin-top: 0px;">Gerente de estación</p>
                        </td>
                        <td style="text-align:center;width:50%;">
                            <hr size="2" width="80%" align="center" color="black" style="margin-bottom: 0px; margin-top: 0px;">
                            <p style="margin-bottom: 0px;margin-top: 0px;">Operador</p>
                        </td>
                    </tr>
                </table>

            <!-- </div> 
        </div>  -->
                </div>
            </div> 
        </div>
        <!-- <footer style="position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;">
            <table  border="0" style="width:100%;">
                <tr>
                    <td style="text-align:center;width:50%;">
                        <hr size="2" width="80%" align="center" color="black" style="margin-bottom: 0px; margin-top: 0px;">
                        <p style="margin-bottom: 0px;margin-top: 0px;">Gerente de estación</p>
                    </td>
                    <td style="text-align:center;width:50%;">
                        <hr size="2" width="80%" align="center" color="black" style="margin-bottom: 0px; margin-top: 0px;">
                        <p style="margin-bottom: 0px;margin-top: 0px;">Operador</p>
                    </td>
                </tr>
            </table>
        </footer> -->
    </body>
</html>