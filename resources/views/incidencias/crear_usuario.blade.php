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
                <div class="card-header">{{ __('Nuevo Usuario') }}</div>

                <div class="card-body">
                    {!!Form::open(array('action'=>'IncidenciasController@RegistrarUsuario','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                            @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ap" class="col-md-4 col-form-label text-md-right">{{ __('Apellidos') }}</label>

                            <div class="col-md-6">
                                <input id="ap" type="text" class="form-control{{ $errors->has('ap') ? ' is-invalid' : '' }}" name="ap" required autofocus>

                                @if ($errors->has('ap'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ap') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" 
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                name="email" 
                                placeholder="correo electrónico válido"
                                required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" 
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="confirm" type="password" class="form-control" name="confirm" required>
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Rol') }}</label>

                            <div class="col-md-6">
                            <select id="role" name="role" class="form-control">
                                <option value="">Selecciona</option>
                                <option value="null">Gerente</option>
                                <option value="admin">Administrador</option>
                                <option value="auditor">Auditor</option>
                                <option value="admin">Sistemas</option>
                                <option value="admin">Mantenimiento</option>
                                <option value="null">Corporativo</option>
                                <option value="indigo">Hotel Indigo</option>
                            </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Estación</label>
                            <div class="col-md-6">
                                <select id="estacion" name="estacion" class="form-control">
                                    
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Selecciona las estaciones a asignar</label>
                            <div class="col-md-6">
                                <div id="div_admin" style="display:none">
                                    @foreach($estaciones as $est) 
                                    <input type="checkbox" id="es_{{ $est->estacion }}" name="es_{{ $est->estacion }}" value="{{ $est->estacion }}" checked>                             
                                    <label>{{ $est->sucursal }}</label><br>
                                    @endforeach
                                </div>
                                <div id="div_no_admin">
                                    <select id="estacion" name="estacion" class="form-control">    
                                        <option value="">Selecciona...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Encargado de área</label>
                            <div class="col-md-6">
                                <!-- <select id="area_atencion" name="area_atencion" class="form-control" required>
                                </select> -->
                                <input type="checkbox" id="area_oper" name="area_oper" value="3" checked>                             
                                <label>Operaciones</label><br>
                                <input type="checkbox" id="area_mant" name="area_mant" value="2">                             
                                <label>Mantenimiento</label><br>
                                <input type="checkbox" id="area_sist" name="area_sist" value="1">                             
                                <label>Sistemas</label><br>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fas fa-window-close fa-1x"></i>&nbsp;Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="btn_guardar" name="btn_guardar" onclick="myFunction()">
                                    <i class="fas fa-plus-square fa-1x"></i>&nbsp;Guardar</button>
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
    // $.get('{{ url("/") }}/areas_atencion',{id: ''},function(data)
    // {
    //     $('#area_atencion').empty();
    //     $('#area_atencion').append('<option value="">Selecciona</option>');
    //     $.each(data,function(fetch, miobj)
    //     {
    //         $('#area_atencion').append('<option value="' + miobj.id + '">' + miobj.descripcion + '</option>');
    //     });
    // });

    // $.get('{{ url("/") }}/estaciones','',function(data)
    // {
    //     $('#estacion').empty();
    //     $('#estacion').append('<option value="">Selecciona</option>');
    //     $.each(data,function(fetch, miobj)
    //     {
    //         $('#estacion').append('<option value="' + miobj.estacion + '">' + miobj.sucursal + '</option>');
    //     });
    // });

    $('#btn_guardar').click(function(){
        var pss=$("#password").val();
        var conf_pss=$("#confirm").val();

        if (pss==conf_pss)
        {
            hasError = false;
        }else{        
            alert("No coinciden las contraseñas, favor de verificar");
            hasError = true;
        }
        if(hasError) event.preventDefault();
    });
                    
});  

$(function(){
    $('#role').change(event => {
        var rol=$('select[name=role] option:selected').text();
        var permiso=$('select[name=role]').val();

        alert(permiso);

        if(permiso=="admin" || permiso=="auditor")
        {
            $('#div_no_admin').css("display", "none");
                $('#div_admin').css("display", "block");
        }else{
            $('#div_admin').css("display", "none");
            $('#div_no_admin').css("display", "block");
            $('#estacion').empty();

            if(rol=="Hotel Indigo"){
                $('#estacion').append('<option value="H. INDIGO">Hotel Indigo</option>');                
            }
            else if(rol=="Corporativo"){
                $('#estacion').append('<option value="CORPORATIVO">Corporativo</option>');
            }else{
                $.get('{{ url("/") }}/estaciones','',function(data)
                {
                    $('#estacion').append('<option value="">Selecciona</option>');
                    $.each(data,function(fetch, miobj)
                    {
                        $('#estacion').append('<option value="' + miobj.estacion + '">' + miobj.sucursal + '</option>');
                    });
                });
            }
        }
        
    });
});
</script>
@endsection