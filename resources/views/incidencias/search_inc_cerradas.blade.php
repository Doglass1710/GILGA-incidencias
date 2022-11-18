{!! Form::open(array('url'=>'incidencias_cerradas','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
    <div class="input-group">
        
        <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search fa-1x"></i>&nbsp;buscar</button>
        </span>
        
    </div>
    
</div>

{{ Form::close() }}

