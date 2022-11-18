@extends('layouts.app')

@section('content')
    <div class="container">        
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Html</div>     
                                   
                    <div class="card-body">
                    
                        <div class="form-row">
                            <div class="form-group col-md-1">

                                <div id="div_ok">                           
                                    <span class="fa fa-check-circle" style="color:green; font-size: 40px;"></span> 
                                </div>
                                <div id="div_no">
                                    <span class="fa fa-times-circle" style="color:red; font-size: 40px;"></span> 
                                </div>

                            </div>                            
                            <div class="form-group col-md-11">

                                <h2><div id="divmsj">{{ $msj }}</div></h2>

                            </div>                    
                        </div>
                        <br/><br/>

                        <a class="btn btn-info" href="{{ url('reporte_sisa') }}">
                            <i class="fas fa-hand-point-left fa-1x"></i>&nbsp;Regresar
                        </a>
                    </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection


@section('script')
<script>
$(function(){
    var msj=$("#divmsj").html();
    if(msj=="INGRESA UN ARCHIVO PDF VALIDO"){
        $("#div_ok").css("display", "none");
    }else{
        $("#div_no").css("display", "none");
    }
});
</script>
@endsection