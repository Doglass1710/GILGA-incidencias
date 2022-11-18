@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header">Capturar Detalle Incidencia</div>                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('detalle_send', $incidencia->id) }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group form-row">
                                @if($incidencia->id_usuario != Auth::id())
                                <label class="alert alert-warning col-md-12">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No puedes dar de baja la incidencia, fué generada por el usuario:
                                    <b>{{ $users->where('id', $incidencia->id_usuario)->first()->name }}</b> 
                                    <i class="fas fa-exclamation-triangle"></i>
                                </label> 
                                @endif
                                
                                <div class="form-group col-md-12">
                                    <label for="comentarios">Comentarios</label>
                                    <textarea style="overflow:auto;resize:none" class="form-control" rows="3" id="comentarios" name="comentarios" required maxlength="255"></textarea>
                                    @if ($errors->has('comentarios'))
                                   <span class="invalid-feedback d-block"  role="alert">
                                        <strong>{{ $errors->first('comentarios') }}</strong>
                                   </span>
                                   @endif
                                </div>   
                            </div>
                            
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-8">
                                    <label for="foto_ruta">Selecciona foto</label>
                                    <input type="file" class="form-control" id="foto_ruta" name="foto_ruta">
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="estatus">Estatus</label>
                                    <select id="estatus" class="form-control" name="estatus">
                                    <option selected>En Proceso</option>
                                    <option>Terminado</option>
                                    <!--<option>Pendiente</option>-->
                                    </select>
                                </div>
                                
                            </div>                                     
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <a href="javascript:history.back(-1);"><button type="button" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button></a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Crear Detalle</button>                                    
                                </div>
                            </div>                            
                            
                        </form>
                        <!--{!!Form::close()!!}-->
                        
                    </div>                    
                </div>                
            </div>            
        </div>        
    </div>
@endsection

