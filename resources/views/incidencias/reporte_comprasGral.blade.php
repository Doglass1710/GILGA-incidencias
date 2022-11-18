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
                    <div class="card-header">Reporte Bitácora de Compras</div>                    
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@reporte_comprasGral_Exp','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        
                            @csrf
                            
                            
                            <div class="form-group form-row">

                                <div class="form-group col-md-6">
                                <label for="estacion">Estacion: </label>
                                    <select id="estacion" name="estacion" class="form-control" required>
                                        <option value="todas">Todas</option>
                                    </select>
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label>Selecciona el Mes</label>
                                    <select id="mes" name="mes" class="form-control" required>
                                        <option value="">Selecciona una Mes...</option>
                                        @foreach($meses as $mes)
                                        <option value="{{$mes->MES_NUM}}">{{$mes->MES_LETRA}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="aux_mes" name="aux_mes" hidden>
                                </div>
                                
                            </div>   
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-download fa-1x"></i>&nbsp;Generar Reporte</button>                                    
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
 $(document).ready(function() {

    $('#mes').change(event => {
        var mes = $('select[name=mes]').find('option:selected').text();
        $("#aux_mes").val(mes);
        });
    });
</script>
@endsection