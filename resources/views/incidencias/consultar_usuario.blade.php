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
                <div class="card-header">{{ __('Consultar Usuario') }}</div>

                    <!--Tabla-->
                    @if($consulta->count()>=1)
                    <div class="table-responsive" id="div_tabla">
                        <table id="myTable" class="table table-striped  table-bordered table-condensed table-hover" style="font-size:12px;">
                            <thead class="table table-bordered">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>  
                            <th>Permisos</th>
                        </thead>
                            @foreach($consulta as $cc)
                        <tr>
                            <td>{{ $cc->id }}</td>
                            <td>{{ $cc->name }}</td>
                            <td>{{ $cc->email }}</td>
                            <td>{{ $cc->role }}</td>
                        </tr>
                             @endforeach
                        </table>
                    </div>
                 @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection