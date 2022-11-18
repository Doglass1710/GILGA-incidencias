@extends('layouts.app')

@section('content')
    <div class="container">        
        <div class="row justify-content-center">            
            <div class="col-md-8">                
                <div class="card">
                    <div class="card-header"><h5>Agregar Refacciones: tool 125 Honda y cargo Honda 150</h5></div>    
                    @include('incidencias.bitacora_editarpieza')                
                    <div class="card-body">
                        {!!Form::open(array('action'=>'IncidenciasController@bitacora_catalogo','method'=>'POST','autocomplete'=>'off','enctype'=>'multipart/form-data'))!!}
                        {{Form::token()}}
                        <!--<form method="POST" action="{{ url('generar_reporte_incidencias') }}" autocomplete="off" enctype="multipart/form-data">-->
                            @csrf
                            
                            
                            <div class="form-group form-row">
                                
                                <div class="form-group col-md-2">
                                    <label>Descripción</label>
                                </div>                                       
                                <div class="form-group col-md-10">
                                    <textarea style="overflow:auto;resize:none" class="form-control" id="descripcion" name="descripcion" required></textarea>
                                </div>

                            </div>                                     
                            
                            <div class="form-group form-row">
                                <div class="ml-auto">
                                    <button type="reset" class="btn btn-secondary"><i class="fas fa-window-close fa-1x"></i>&nbsp;Limpiar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-square fa-1x"></i>&nbsp;Agregar</button>                                    
                                </div>
                            </div>                            
                            
                        <!--</form>-->
                        
                    </div>                    
                </div>                
            </div>   
            <div class="col-md-8" style="padding-top:20px;">                
                <div class="card">
                    <div class="card-header"><h5>Consultar</h5></div>                    
                    <div class="card-body">
                            @csrf                                       
                            <div class="form-group form-row">
                                <!--Tabla-->
                                <div class="table-responsive" id="div_tabla">
                                    <table id="myTable" class="table-bordered table-condensed table-hover" style="width:100%;text-align:center">
                                        <thead class="table table-bordered">
                                            <th>ID</th>
                                            <th>Descripcion</th>
                                            <th>Editar</th>
                                        </thead>
                                        @foreach ($catalogo ?? '' as $bit)
                                        <tr>                        
                                            <td>{{ $bit->id }}</td>
                                            <td>{{ $bit->descripcion }}</td>   
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#bitacora_editarpieza" data-id="{{$bit->id}}" data-des="{{ $bit->descripcion }}">
                                                <button class="btn btn-warning"><i class="fa fa-edit fa-1x"></i></button></a>
                                            </td>                
                                        </tr>
                                        @endforeach
                                    </table>
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
                
        $('#myTable').DataTable({
        "language": {
            "sProcessing":     "Procesando...",
            "lengthMenu": "Mostrar _MENU_ filas",
            "zeroRecords": "No hay coincidencias",
            "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ total de registros)",
            "sSearch":         "Buscar:",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        });

        $('#bitacora_editarpieza').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var descripcion= button.data('des')
            var modal = $(this)

            modal.find('.modal-title').text('Editar Incidencia: ID: ' + id)
            modal.find('#id').val(id)
            modal.find('#descripcion').val(descripcion)
        });

     

    });
</script>
@endsection 