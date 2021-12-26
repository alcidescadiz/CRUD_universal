<div>
    <div class="form-group"><a href="{{route('getdb')}}" class="btn btn-primary">Cambiar la Base de datos</a></div>
    <h2>Buscar y seleccionar una tabla:</h2>
    <form action="{{route('tabla')}}" method="get" class="form-group" style="width:350px">
        @csrf
        <div class="form-group">
            <select class="form-control" name="nombre_tabla" required>
               @if ( isset($nombre)  )
                <option value="{{$nombre}}">{{$nombre}}</option>
               @else
               <option value="">Seleccione una tabla de la base de datos</option>
               @endif
                @forelse ($tablas as $item)
                    <option value="{{$item}}">{{$item}}</option>  
                @empty
                @endforelse
            </select>
         </div>
         <input type="hidden" name="nombre_bd" value="{{$database}}">
        <div class="form-group"><button type="submit" class="btn btn-primary">BUSCAR</button></div>
    </form>
</div>