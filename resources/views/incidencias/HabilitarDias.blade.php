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
                    <div class="card-header">Medidas Diarias</div>     
                                   
                    <div class="card-body">
                        {!!Form::open(array('method'=>'POST','action'=>'IncidenciasController@HabilitarDias','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                
                            </div>
                            <div class="form-group col-md-8">
                                ¿Cuantos días vas a habilitar?
                            </div>                    
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                            </div>   
                            <div class="form-group col-md-4">                                
                                <input type="number" id="dias" name="dias" min="0" max="30" class="form-control" value="1" required/>
                            </div>      
                            <div class="form-group col-md-4">
                            </div>                 
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{{ url('captura_medidas') }}">
                                    <div class="btn btn-secondary"><i class="fas fa-hand-point-left fa-1x"></i>&nbsp;Regresar</div>
                                </a>
                                <button type="submit" id="btn" name="btn" class="btn btn-primary">
                                    <i class="fas fa-check fa-1x"></i>&nbsp;Habilitar
                                </button>                               
                            </div>
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