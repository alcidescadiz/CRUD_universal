<div class="form-group"><a href="{{route('getdb')}}" class="btn btn-outline-primary">Cambiar la Base de datos</a></div>
<h3>seleccionar una tabla:</h3>
<div style="width:90%" >
    <form action="{{route('tabla')}}" method="post"   > 
        @csrf
        <div  class="btn-group">
            <div style="padding: 5" >
                <select class="form-control" name="nombre_tabla" required  style="width:350px; ">
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
            <input type="hidden" name="nombre_bd" value="{{$database}}"  >
            <div style="margin: 5" >
                <button type="submit" style="width:100px; " class="btn btn-outline-success" >BUSCAR</button>
            </div>
        </div>
    </form>
</div>
<br>