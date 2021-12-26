@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">
    
    
    <x-tablas.buscar :tablas="$tablas"  :nombre="$nombre" :database="$database"/>
    
    <div>
        <h2>Crear en Tabla: {{$nombre}}</h2>
        <form action="{{route('tablas.store')}}" method="post" class="form-control-sm">
            @csrf
            <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
            <input type="hidden" name="nombre_bd" value="{{$database}}">  
                @for ($i = 0; $i < count($header); $i++)
                    <?php $key =$header[$i]->COLUMN_NAME; ?>
                    @if ( $key != 'created_at' && $key != 'updated_at' && $key != 'id' && $key != 'estatus' && $key != 'deleted_at' )
                        <div class="form-group">
                            <label for="">{{$key}}</label>
                            <input   
                            @if ($header[$i]->DATA_TYPE === 'double' || $header[$i]->DATA_TYPE === 'int' ||  $header[$i]->DATA_TYPE === 'bigint')
                                    type="number"  step="0.01" 
                            @elseif ($header[$i]->DATA_TYPE === 'date')
                                    type="date"  
                            @else
                                    type="text"
                            @endif   name="{{$key}}"  required class="form-control" >
                        </div>
                    @endif
                @endfor
            <div class="form-group"><button type="submit" class="btn btn-primary">Guardar cambios</button></div>
        </form>
    </div>          
</div>
@endsection