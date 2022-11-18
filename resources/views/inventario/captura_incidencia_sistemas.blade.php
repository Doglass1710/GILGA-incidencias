@extends('layouts.app')

@section('content')

    <div class="container">  
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Capturar Incidencias de Sistemas</div>   
                    
                    <div class="card-body">
                        
                        {!!Form::open(array('action'=>'InventarioController@incidencias_sistemas','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf
                            <div class="form-group form-row">                                                                
                                <div class="col-md-4">
                                    <label>Estacion</label>
                                    <select id="estacion" name="estacion" class="form-control" required>                                    
                                        <option value="" selected>Selecciona Estacion...</option>
                                        @foreach($estaciones as $estacion)
                                        <option value="{{$estacion->estacion}}">{{$estacion->sucursal}}</option>
                                        @endforeach
                                    </select>
                                </div> 
                                <div class="col-md-4">
                                    <label>Folio incidencia</label>
                                    <input type="text" class="form-control" id="folio" name="folio" required readonly>
                                    @if ($errors->has('folio'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('folio') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="prioridad">Prioridad</label>
                                    <input type="text" class="form-control" id="prioridad" name="prioridad" required readonly>
                                </div>
                            </div>
                            
                            <div class="form-group form-row">
                                <div class="col-md-4">
                                    <label>Area</label>
                                    
                                    <select id="area" name="area" class="form-control" required>
                                        <option value="">Selecciona..</option>
                                        @foreach($area as $ar)
                                        <option value="{{ $ar->id }}">{{ $ar->descripcion }}</option>
                                        @endforeach
                                        <!-- <option value="otro">OTRO...</option> -->
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label>Subarea</label>
                                    <select id="subarea" name="subarea" class="form-control" required>         
                                    </select>
                                </div>  
                                
                                <div class="col-md-4">
                                    <label>Equipo</label>                                    
                                    <select id="equipos" name="equipos" class="form-control" required>              
                                    </select>
                                </div>   
                            </div>

                            <div class="form-group form-row">
                                <div class="col-md-3">
                                    <label>ID</label>
                                    <select id="folio_equipo" name="folio_equipo" class="form-control" required>              
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Marca</label>
                                    <input type="text" id="marca" name="marca" class="form-control" readonly/>
                                </div>                                
                                <div class="col-md-3">
                                    <label>Modelo</label>
                                    <input type="text" id="modelo" name="modelo" class="form-control" readonly/>
                                </div>                                  
                                <div class="col-md-3">
                                    <label>Serie</label>
                                    <input type="text" id="serie" name="serie" class="form-control" readonly/>
                                </div>       
                            </div>

                            <div id="div_incidencia">
                                <!-- <div class="form-group form-row">                                
                                    <div class="col-md-8">
                                        <label>Asunto</label>
                                        <input type="text" class="form-control" 
                                        id="asunto" 
                                        name="asunto" 
                                        placeholder="Asunto..." 
                                        maxlength="50"
                                        required />
                                        @if ($errors->has('asunto'))
                                            <span class="invalid-feedback d-block"  role="alert">
                                                <strong>{{ $errors->first('asunto') }}</strong>
                                            </span>
                                        @endif                                    
                                    </div>                                     
                                    <div class="col-md-4">
                                        <label>Selecciona foto</label>
                                        <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_ruta" name="foto_ruta" required />
                                    </div>                           
                                </div> -->
                                <div class="form-group form-row">  
                                    <div class="col-md-4">
                                        <label>Selecciona foto</label>
                                        <input type="file" accept=".jpg, .png, .jpeg" class="form-control" id="foto_ruta" name="foto_ruta" required />
                                    </div>                              
                                    <div class="col-md-8">
                                        <label>Comentario</label>
                                        <textarea style="overflow:auto;resize:none" 
                                            class="form-control"  
                                            id="descripcion" 
                                            name="descripcion" 
                                            maxlength="255">
                                        </textarea>
                                        @if ($errors->has('descripcion'))
                                        <span class="invalid-feedback d-block"  role="alert">
                                            <strong>{{ $errors->first('descripcion') }}</strong>
                                        </span>
                                        @endif
                                    </div>       
                                </div>
                            </div>

                            <div class="form-group form-row">
                                <div class=" ml-auto">  
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" id="btn_Guardar" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Crear Incidencia</button>                                    
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
$(document).ready(function(){  

    $('#estacion').change(event=>{
        $("#area").prop("selectedIndex", 0);
        $("#subarea").prop("selectedIndex", 0);
        $("#equipos").prop("selectedIndex", 0);
        $("#folio").empty();  
        $("#prioridad").empty();  
    });

    $('#area').change(event=>{
        var area=$('select[name=area]').val();

        $.get('{{ url("/") }}/subareas',{area:area},
        function(data){
            $("#subarea").empty();
            $("#marca").empty();
            $("#modelo").empty();
            $("#serie").empty();

            $("#subarea").append('<option value="" selected>Selecciona una opción...</option>'); 
             $.each(data,function(fetch, miobj){                
                $("#subarea").append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>'); 
           });
        });

        $.get('{{ url("/") }}/inventario_equipos',{area:area},
        function(data){
            $("#equipos").empty();
            $("#equipos").append('<option value="" selected>Selecciona una opción...</option>'); 
             $.each(data,function(fetch, miobj){
                $("#equipos").append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>'); 
           });
        });

        var tipo_solicitud = "incidencia";
        var est = $('select[name=estacion]').val();
        $.get('{{ url("/") }}/folios',{estacion: est, tipo_solicitud: tipo_solicitud},function(data){
            $("#folio").val(data);   
            $("#prioridad").val("alta");
        });
    });

    $('#subarea').change(event=>{
        //$("#equipos").prop("selectedIndex", 0);
        $("#folio_equipo").empty();
        $("#marca").empty();
        $("#modelo").empty();
        $("#serie").empty();

        var subarea = $('select[name=subarea]').val();
        var area=$('select[name=area]').val();
        $("#equipos").empty();

        if(subarea >=5 && subarea <=8)
        {
            $("#equipos").append('<option value="" selected>Selecciona una opción...</option>');
            if(subarea == 5){
                $("#equipos").append('<option value="18">DVR</option>'); 
            }else if (subarea ==6){ 
                $("#equipos").append('<option value="19">VEEDER ROOT</option>'); 
            }else if (subarea ==7){
                $("#equipos").append('<option value="20">CONMUTADOR</option>'); 
            }else if (subarea ==8){
                $("#equipos").append('<option value="21">IMPRESORA RENTA</option>'); 
            }
        }
        else{
            $.get('{{ url("/") }}/inventario_equipos',{area:area},
            function(data){
                $("#equipos").empty();
                $("#equipos").append('<option value="" selected>Selecciona una opción...</option>'); 
                $.each(data,function(fetch, miobj){
                    $("#equipos").append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>'); 
                });
            });
        }

    });

    $('#equipos').change(event=>{
        var equipo=$('select[name=equipos]').val();
        var subarea = $('select[name=subarea]').val();
        var est = $('select[name=estacion]').val();
        $("#marca").val("");
        $("#modelo").val("");
        $("#serie").val("");

        $.get('{{ url("/") }}/folio_equipos',{estacion: est, equipo: equipo, subarea: subarea},function(data){
            $("#folio_equipo").empty();

            $.each(data,function(fetch, miobj){
                $("#folio_equipo").append('<option value="' + miobj.folio + '">' + miobj.folio + '</option>'); 
                $("#marca").val(miobj.marca);
                $("#modelo").val(miobj.modelo);
                $("#serie").val(miobj.serie);
           });
        });

    });
});
</script>
@endsection






