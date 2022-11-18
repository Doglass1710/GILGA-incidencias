<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Orden de Compra</title>
        
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body>
        
        <img src="{{ asset('images/logo_gilga.png') }}" alt="Logo" height="61" width="120">
        
        <div class="contenido">            
            
            <table class="table table-bordered">
            <tr><th COLSPAN="8" style="text-align:center">ORDEN DE COMPRA</th></tr>
            <tr>
                <th>Estación</th>
                @foreach ($estacion as $est)
                <td COLSPAN="7">{{$est->estacion."-".$est->nombre_corto}}</td>
                @endforeach
            </tr>
            <tr>
                <th>Autorizó</th>
                
                @foreach ($compras as $c)
                    @if($c->usuario_autoriza==null)
                        <td COLSPAN="7">No Autorizada</td>
                    @else
                        <td COLSPAN="7">{{ $users->where("id", $c->usuario_autoriza)->first()->name }}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <th COLSPAN="2">Fecha Compra</th>
                <th COLSPAN="2">Proveedor</th>
                <th COLSPAN="3">Facturar A</th>
                <th>Folio</th>                
            </tr>
            @foreach ($compras as $c)
            <tr>
                <td COLSPAN="2">{{ $c->fecha_compra }}</td>
                <td COLSPAN="2">{{ $c->proveedor_razon_social }}</td>
                <td COLSPAN="3">{{ $c->compania_razon_social }}</td>
                <td >{{ $c->folio }}</td>
                
            </tr>
            @endforeach
            
            <tr><th COLSPAN="8" style="text-align:center">Detalles</th></tr>

            <tr>
                <th>Incidencia</th> 
                <th>Moneda</th>
                <th>Tipo Cambio</th>               
                <th COLSPAN="2">Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>                
                
            </tr>
            @foreach($compras_detalle as $cd)
            <tr>
                <td>{{ $cd->id_incidencia }}</td> 
                <td>{{ $cd->moneda }}</td>
                <td>{{ $cd->tipo_cambio }}</td>               
                <td COLSPAN="2">{{ $cd->producto_descripcion  }}</td>
                <td>{{ $cd->cantidad }}</td>
                <td>${{ $cd->precio_unitario  }}</td>
                <td>${{ $cd->total  }}</td>
                                
            </tr>
            @endforeach

            @foreach ($compras as $c)
            <tr>
                <td COLSPAN="2"></td>
                <td COLSPAN="2">Subtotal: ${{ $c->subtotal }}</td>
                <td COLSPAN="2">IVA: ${{ $c->iva }}</td>
                <td COLSPAN="2">Total: ${{ $c->total }}</td>
            </tr>
            @endforeach
            
            </table>
        </div>
    </body>
</html>