

     
<div class="form-group">
    <form method="POST" action="{{ route("tablas.create") }}">
        @csrf
        <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
        <input type="hidden" name="nombre_bd" value="{{$database}}">
        <button class="btn btn-primary" type="submit">CREAR FILA EN TABLA {{$nombre}}</button>
    </form>
</div>
    

  
    <table class="table table-striped  table-responsive" style="width:90%" id="myTable" >
        <thead>
            <tr> 
            @for ($i = 0; $i < count($header); $i++)
                @if ( $header[$i]->COLUMN_NAME != 'created_at' && $header[$i]->COLUMN_NAME != 'updated_at' && $header[$i]->COLUMN_NAME != 'deleted_at' )
                    <th>{{$header[$i]->COLUMN_NAME}}</th>
                @endif
            @endfor 
                <th colspan="3" style="text-align: center">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
           @forelse ($body as $item)
                    <tr>
                        @for ($i = 0; $i < count($header); $i++)
                            <?php $key =$header[$i]->COLUMN_NAME; $noid =$header[0]->COLUMN_NAME;?>
                            @if ( $key != 'created_at' && $key != 'updated_at' && $key != 'deleted_at')
                                <td data-titulo="{{$key}}">{{$item->$key}}</td>
                            @endif
                        @endfor
                            <td style="text-align: center">
                                <form method="POST" action="{{ route("tablas.edit") }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id"  value="{{$item->$noid}}">
                                    <input type="hidden" name="key_id"  value="{{$header[0]->COLUMN_NAME}}">
                                    <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
                                    <input type="hidden" name="nombre_bd" value="{{$database}}">
                                    <button class="btn btn-primary" type="submit">Editar</button>
                                </form>
                            </td>
                            <td style="text-align: center">
                                <form method="POST"  action="{{ route("tabla.delete" , $item->$noid)}}"  >
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{$item->$noid}}"> 
                                    <input type="hidden" name="key_id"  value="{{$header[0]->COLUMN_NAME}}">
                                    <input type="hidden" name="nombre_tabla" value="{{$nombre}}"> 
                                    <input type="hidden" name="nombre_bd" value="{{$database}}">
                                     <x-eliminar :ide="$item->$noid"  /> 
                                </form>
                            </td>
                    </tr>
            @empty
            <tr> 
                <th  colspan="{{count($header)}}" style="text-align: center">No hay registros disponibles</th>
            </tr> 
            @endforelse
        </tbody>
    </table>
    
