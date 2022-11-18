@extends('layouts.app')

@section('content')

    <div class="container">  
    <!-- @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif -->

    <div class="alert alert-success" id="divmsj">
        {{ $message ?? ''}}
    </div>
    
        <div class="row justify-content-center">            
            <div class="col-md-10">                
                <div class="card">
                    <div class="card-header" style="text-align:center"><h5>Consolidado</h5>
                    </div>   
                    
                    <div class="card-body" onload="" >
                        
                        {!!Form::open(array('method'=>'POST','action'=>'IncidenciasController@consolidado','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            <div class="form-row">                            
                                <div class="form-group col-md-3">
                                    <label>Estación: </label>                                    
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        @if($role=="admin")
                                            <option value="" selected>Selecciona Estacion...</option>   
                                        @endif 
                                        @foreach($estaciones as $estacion)                                        
                                            <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>   
                                        @endforeach                                     
                                    </select>
                                </div>                                
                                <div class="form-group col-md-6">
                                    <label>Razón social: </label>
                                    <label class="form-control" id="lbl_razon_Social">
                                        @if($role<>"admin")                              
                                            {{ $estacion->razon_social }}
                                        @endif 
                                    </label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Fecha</label>
                                    <input type="date" id="fecha" name="fecha" class="form-control" min="2021-01-01" onchange="myfecha();" required/>
                                </div>
                            </div>      
                            
                            <div class="card">
                                <div class="card-header" style="text-align:center"><h5>Bancos</h5></div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label>Cta Prinicpal Santander: </label>
                                            <label>Cta Super</label>
                                            <label>Cta Prinicpal Banamex: </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" style="width:100%" id="txt_ctaPrincipalSTD"name="txt_ctaPrincipalSTD"  class="ctaBancos" value="0" onkeyup="sumar('.ctaBancos','txt_ctaTotalBancos');" required />
                                            <input type="text" style="width:100%" id="txt_ctaSuper" name="txt_ctaSuper"  class="ctaBancos"  value="0" onkeyup="sumar('.ctaBancos','txt_ctaTotalBancos');" required/>
                                            <input type="text" style="width:100%" id="txt_ctaPrincipalBMX" name="txt_ctaPrincipalBMX" class="ctaBancos" value="0" onkeyup="sumar('.ctaBancos','txt_ctaTotalBancos');" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label>Línea de crédito Santander: </label>
                                            <label>Línea de crédito Banamex: </label>
                                            <label>Línea de crédito Banorte: </label>
                                            <label>Unión de crédito: </label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" style="width:100%" id="txt_lnCredSTD" name="txt_lnCredCredSTD" class="lnCred" value="0" onkeyup="sumar('.lnCred','txt_lnCredTotalBancos');" required />
                                            <input type="text" style="width:100%" id="txt_lnCredBMX" name="txt_lnCredBMX" class="lnCred" value="0" onkeyup="sumar('.lnCred','txt_lnCredTotalBancos');" required />
                                            <input type="text" style="width:100%" id="txt_lnCredBNT" name="txt_lnCredBNT" class="lnCred" value="0" onkeyup="sumar('.lnCred','txt_lnCredTotalBancos');" required />
                                            <input type="text" style="width:100%" id="txt_unionCredito" name="txt_unionCredito" class="lnCred" value="0" onkeyup="sumar('.lnCred','txt_lnCredTotalBancos');" required />
                                        </div>
                                    </div> 

                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label>Total: </label>                                            
                                        </div> 
                                        <div class="col-md-3">
                                           <input type="text" style="width:100%" id="txt_ctaTotalBancos" name="txt_ctaTotalBancos" readonly/>  
                                        </div> 
                                        <div class="col-md-3">
                                            <label>Total: </label>                                            
                                        </div> 
                                        <div class="col-md-3">
                                            <input type="text" style="width:100%" id="txt_lnCredTotalBancos" name="txt_lnCredTotalBancos" readonly />                                          
                                        </div> 
                                    </div>
                                    <!-- <div class="form-row">
                                    <span>Valor #1</span>
                                    <input type="text" id="txt_campo_1" class="monto" onkeyup="sumar_ejemplo();" />
                                    <br/>

                                    <span>Valor #2</span>
                                    <input type="text" id="txt_campo_2" class="monto" onkeyup="sumar_ejemplo();" />
                                    <br/>

                                    <span>Valor #3</span>
                                    <input type="text" id="txt_campo_3" class="monto" onkeyup="sumar_ejemplo();" />
                                    <br/>

                                    <span>El resultado es: </span> <span id="spTotal"></span>
                                    </div> -->

                                    <div class="dropdown-divider"></div><br/>

                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>Num. de clientes: </label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="" id="txt_bnClientes" name="txt_bnClientes" required />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Saldo de clientes: </label>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="text" style="width:100%" class="" id="txt_bnTotalCtes" name="txt_bnTotalCtes" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="text" style="width:100%" class="" id="txt_bnSumaImportes" name="txt_bnSumaImportes" readonly />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <label>Concepto </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Pdt. x facturar </label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>Corriente </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vencido 01-07 días </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vencido 08-14 días </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vencido 15-21 días </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vencido 22 o más </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-1">
                                            <label>Importe</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnPdtFacturar" name="txt_bnPdtFacturar" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-1">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnCorriente" name="txt_bnCorriente" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnVencido1_7" name="txt_bnVencido1_7" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnVencido8_14" name="txt_bnVencido8_14" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnVencido15_21" name="txt_bnVencido15_21" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" class="bnImportes" id="txt_bnVencido22" name="txt_bnVencido22" onkeyup="sumar_restar('.bnImportes','txt_bnSumaImportes','txt_bnTotalCtes');" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label>Observaciones</label>
                                        </div>
                                        <div class="form-group col-md-10">
                                        <textarea style="overflow:auto;resize:none" class="form-control"  id="txa_bnObservaciones" name="txa_bnObservaciones" required maxlength="255"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" style="text-align:center"><h5>Resumen de ventas semanal</h5></div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <label>FECHA</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>PREMIUM</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>MAGNA</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>DIESEL</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>SUBTOTAL</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>MAYOREO</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>V.TOTAL</label>
                                        </div>
                                    </div> 
                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <div style="margin-bottom: 0.4rem;"><span id="spn_fchVentas1">aqui for</span></div>
                                            <div style="margin-bottom: 0.4rem;"><span id="spn_fchVentas2"></span></div>
                                            <div style="margin-bottom: 0.4rem;">24 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">23 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">22 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">21 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">20 Oct</div>
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_premiumFecha{{$dias}}" name="txt_premiumFecha{{$dias}}" class="VentasFecha{{$dias}}" onkeyup="sumar('.VentasFecha{{$dias}}','txt_subtotalFecha{{$dias}}');" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_magnaFecha{{$dias}}" name="txt_magnaFecha{{$dias}}" class="VentasFecha{{$dias}}" onkeyup="sumar('.VentasFecha{{$dias}}','txt_subtotalFecha{{$dias}}');" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_dieselFecha{{$dias}}" name="txt_dieselFecha{{$dias}}" class="VentasFecha{{$dias}}" onkeyup="sumar('.VentasFecha{{$dias}}','txt_subtotalFecha{{$dias}}');" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_subtotalFecha{{$dias}}" name="txt_subtotalFecha{{$dias}}" required />
                                            @endfor
                                            <!-- <input type="text" style="width:100%" id="txt_subtotalFecha2" name="txt_subtotalFecha2" required />
                                            <input type="text" style="width:100%" id="txt_subtotalFecha3" name="txt_subtotalFecha3" required />
                                            <input type="text" style="width:100%" id="txt_subtotalFecha4" name="txt_subtotalFecha4" required />
                                            <input type="text" style="width:100%" id="txt_subtotalFecha5" name="txt_subtotalFecha5" required />
                                            <input type="text" style="width:100%" id="txt_subtotalFecha6" name="txt_subtotalFecha6" required />
                                            <input type="text" style="width:100%" id="txt_subtotalFecha7" name="txt_subtotalFecha7" required /> -->
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha{{$dias}}" name="txt_mayoreoFecha{{$dias}}" required />
                                            @endfor
                                            <!-- <input type="text" style="width:100%" id="txt_mayoreoFecha1" name="txt_mayoreoFecha1" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha2" name="txt_mayoreoFecha2" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha3" name="txt_mayoreoFecha3" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha4" name="txt_mayoreoFecha4" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha5" name="txt_mayoreoFecha5" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha6" name="txt_mayoreoFecha6" required />
                                            <input type="text" style="width:100%" id="txt_mayoreoFecha7" name="txt_mayoreoFecha7" required /> -->
                                        </div>
                                        <div class="col-md-1">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha{{$dias}}" name="txt_ventaTotalFecha{{$dias}}" required />
                                            @endfor
                                            <!-- <input type="text" style="width:100%" id="txt_ventaTotalFecha1" name="txt_ventaTotalFecha1" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha2" name="txt_ventaTotalFecha2" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha3" name="txt_ventaTotalFecha3" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha4" name="txt_ventaTotalFecha4" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha5" name="txt_ventaTotalFecha5" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha6" name="txt_ventaTotalFecha6" required />
                                            <input type="text" style="width:100%" id="txt_ventaTotalFecha7" name="txt_ventaTotalFecha7" required /> -->
                                        </div>
                                    </div>       
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>IMPORTE VENTA DEL DIA: </label>                                            
                                        </div> 
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" id="txt_importeVta" name="txt_importeVta" required />                                            
                                        </div> 
                                        <div class="form-group col-md-2">
                                            <label>CONTADO </label>                                            
                                        </div> 
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" id="txt_contado" name="txt_contado" required />                                          
                                        </div> 
                                        <div class="form-group col-md-1">
                                            <label>CREDITO </label>                                            
                                        </div> 
                                        <div class="form-group col-md-2">
                                            <input type="text" style="width:100%" id="txt_credito" name="txt_credito" required />                                          
                                        </div> 
                                    </div>   
                            
                                    <!-- <div class="dropdown-divider"></div> -->

                                    <div class="form-row">
                                        <div class="card-footer form-group col-md-6" style="text-align:center"><label>Ventas de Super (En caso de aplicar)</label></div>
                                        <div class="card-footer form-group col-md-6" style="text-align:center"><label>Ventas aceites zona y E.S.</label></div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <label>FECHA</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>TOTAL</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>VENTA E.S.</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>VENTA ZONA</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>TOTAL</label>
                                        </div>
                                    </div> 
                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <div style="margin-bottom: 0.4rem;">26 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">25 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">24 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">23 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">22 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">21 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">20 Oct</div>
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaSuperA{{$dias}}" name="txt_ventaSuperA{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaSuperB{{$dias}}" name="txt_ventaSuperB{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaSuperTotal{{$dias}}" name="txt_ventaSuperTotal{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaES{{$dias}}" name="txt_ventaES{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaZona{{$dias}}" name="txt_ventaZona{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-1">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaZonaTotal{{$dias}}" name="txt_ventaZonaTotal{{$dias}}" required />
                                            @endfor
                                        </div>
                                    </div>     

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" style="text-align:center"><h5>Venta Tiempo Aire</h5></div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                        <label>FECHA</label>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-3" style="align:center">
                                        <label>VENTA DEL DIA</label>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-3">
                                        <label>SALDO AL CIERRE</label>
                                        </div>
                                    </div> 
                                <div class="form-row">
                                        <div class="col-md-2">
                                            <div style="margin-bottom: 0.4rem;">
                                            <!-- <input type="text" id="lbl_fchVentas1" name="lbl_fchVentas1" value="" readonly style="width:100%"/> -->
                                        </div>
                                            <div style="margin-bottom: 0.4rem;">25 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">24 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">23 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">22 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">21 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">20 Oct</div>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-md-3">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaTiempoAire{{$dias}}" name="txt_ventaTiempoAire{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="col-md-2">
                                        </div>                                        
                                        <div class="col-md-3">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ventaTiempoAireTotal{{$dias}}" name="txt_ventaTiempoAireTotal{{$dias}}" required />
                                            @endfor
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="dropdown-divider"></div><br/>
                            <div class="card">
                                <div class="card-header" style="text-align:center"><h5>Existencia al cierre del día</h5></div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>CONCEPTO</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>PREMIUM</label>
                                        </div>
                                        <div class="col-md-2" style="align:center">
                                            <label>MAGNA</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>DIESEL</label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>OBSERVACIONES</label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>EXISTENCIAS</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2" style="align:center">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>COMPRAS</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2" style="align:center">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                    </div> 
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>TOTAL</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2" style="align:center">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" style="width:100%" id="txt_ex1" name="txt_ex1" required />
                                        </div>
                                    </div> 
                                </div>
                            </div>

                            <div class="dropdown-divider"></div><br/>
                            <div class="card">
                                <div class="card-header" style="text-align:center"><h5>LEMARGO/REPSOL</h5></div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>FECHA</label>                                            
                                        </div>
                                        <div class="col-md-2">
                                            <label>IMPORTE</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>NOTA DE CREDITO</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label>OBSERVACIONES</label>
                                        </div>
                                    </div> 
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <div style="margin-bottom: 0.4rem;">26 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">25 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">24 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">23 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">22 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">21 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">20 Oct</div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_importe{{$dias}}" name="txt_importe{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="form-group col-md-2">
                                        @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_notaCredito{{$dias}}" name="txt_notaCredito{{$dias}}" required />
                                            @endfor
                                        </div>
                                        <div class="form-group col-md-6">
                                        @for($dias=1; $dias<=7; $dias++)
                                            <input type="text" style="width:100%" id="txt_ObservacionesLemargo{{$dias}}" name="txt_ObservacionesLemargo{{$dias}}" required />
                                            @endfor
                                        </div>
                                    </div> 
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>TOTAL</label>                                            
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_p1" name="txt_p1" required />
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_p1" name="txt_p1" required />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" style="width:100%" id="txt_p1" name="txt_p1" required />
                                        </div>
                                    </div> 

                                    <div class="form-row">
                                        <div class="card-footer form-group col-md-6" style="text-align:center"><label>RELACION REPORTES CONTABILIDAD</label></div>
                                        <div class="card-footer form-group col-md-6" style="text-align:center"><label>OBSERVACIONES</label></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label>FECHA</label>
                                        </div>
                                        <div class="col-md-4">
                                            <label>ENVIADO POR COMETRA</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label></label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <div style="margin-bottom: 0.4rem;">26 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">25 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">24 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">23 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">22 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">21 Oct</div>
                                            <div style="margin-bottom: 0.4rem;">20 Oct</div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_p1" name="txt_p1" required />
                                            <input type="text" style="width:100%" id="txt_p2" name="txt_p2" required />
                                            <input type="text" style="width:100%" id="txt_p3" name="txt_p3" required />
                                            <input type="text" style="width:100%" id="txt_p4" name="txt_p4" required />
                                            <input type="text" style="width:100%" id="txt_p5" name="txt_p5" required />
                                            <input type="text" style="width:100%" id="txt_p6" name="txt_p6" required />
                                            <input type="text" style="width:100%" id="txt_p7" name="txt_p7" required />
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" style="width:100%" id="txt_p1" name="txt_p1" required />
                                            <input type="text" style="width:100%" id="txt_p2" name="txt_p2" required />
                                            <input type="text" style="width:100%" id="txt_p3" name="txt_p3" required />
                                            <input type="text" style="width:100%" id="txt_p4" name="txt_p4" required />
                                            <input type="text" style="width:100%" id="txt_p5" name="txt_p5" required />
                                            <input type="text" style="width:100%" id="txt_p6" name="txt_p6" required />
                                            <input type="text" style="width:100%" id="txt_p7" name="txt_p7" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
 
                            <br/>
                            <div class="form-group form-row">

                                <div class=" ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn" name="btn" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Capturar</button>                                    
                                </div>
                            </div>  
                            <br/>
                            
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

$(document).ready(function(){

    $(function(){
//$('#txt_ctaPrincipalSTD,#txt_ctaSuper,#txt_ctaPrincipalBMX,#txt_lnCredSTD,#txt_lnCredBMX,#txt_lnCredBNT,#txt_unionCredito,.bnImportes')
        $('.ctaBancos,.lnCred,.bnImportes,#txt_bnClientes,#txt_bnTotalCtes')
        .keypress(function(e) {
        if(isNaN(this.value + String.fromCharCode(e.charCode))) 
            return false;
        })
        .on("cut copy paste",function(e){
            e.preventDefault();
        });

    });   

});

    //sumar totales
    function sumar(clase,textTotal) {
        var total = 0;
        $(clase).each(function() {
            
        if (isNaN(parseFloat($(this).val()))) {
            total += 0;
        } else {
            total += parseFloat($(this).val());
        }
        });
        document.getElementById(textTotal).value = total;
    }

    function sumar_restar(clase,textTotal,textresta){
        var total = 0;
        var resta = document.getElementById(textresta).value;

        if (isNaN(parseFloat(resta))) {
            resta=0;
        }

        $(clase).each(function() {
            
        if (isNaN(parseFloat($(this).val()))) {
            total += 0;
        } else {
            total += parseFloat($(this).val());
        }
        });
        document.getElementById(textTotal).value = total;        
        total= total-parseFloat(resta);
        
        document.getElementById(textTotal).value = total;   
    }

    //FECHAS https://www.w3schools.com/jsref/jsref_getdate.asp
    function myfecha(){
          var x = document.getElementById("fecha").value; 
          //<input type="date" id="date" onchange="obtenerFecha(this)" />
        //alert(e.value);
        const month = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
        const d = new Date(x);
        let name = month[d.getMonth()];

        $('#spn_fchVentas1').empty();
        $('#spn_fchVentas1').append((d.getDate()) + ' ' + name);

        var nfecha=$("#spn_fchVentas1").html();
        
        //document.getElementById('spn_fchVentas1').innerHTML = x;
        document.getElementById('spn_fchVentas2').innerHTML = nfecha;
    }
          
    

    // function myFunction() {
    // var x = document.getElementById("txt_clientes");
    // x.value = x.value.toUpperCase();
    // }

    // function sumar_ejemplo() {
    //     var total = 0;
    //     $(".monto").each(function() {
    //     if (isNaN(parseFloat($(this).val()))) {
    //         total += 0;
    //     } else {
    //         total += parseFloat($(this).val());
    //     }
    //     });
    // document.getElementById('spTotal').innerHTML = total;
    // }

//         //total = (total == null || total == undefined || total == "") ? 0 : total;


// $(function(){
// var d = new Date();
// //document.write(d.getDate());
// $('#fecha').append(d.getDate());
// });

$(function tanquesload() {
    var msj=$("#divmsj").html();

    if(msj==""){
        $("#divmsj").css("display", "none");
    }else{
        $("#divmsj").css("display", "block");
    }
    
}); 

$(function(){
    $('#estacion').change(event => {
        var estacion =$('select[name=estacion]').val();
           
        $.get('{{ url("/") }}/companias',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#lbl_razon_Social").text(miobj.razon_social);                  
            });
        });

        $.get('{{ url("/") }}/gerentes',{estacion: estacion},function(data){
            $.each(data,function(fetch, miobj){
            $("#txt_gerente").empty();  
            $("#txt_gerente").append('<option value="' + miobj.nombre + '">' + miobj.nombre + '</option>');                  
            });
        });

    });

});

</script>
@endsection






