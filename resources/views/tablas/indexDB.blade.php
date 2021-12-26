@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">

    <div>
        <h2>Buscar y seleccionar una Base de datos:</h2>
        <form action="{{route('tablas')}}" method="get" class="form-group" style="width:300px">
            @csrf
            <div class="form-group">
              <label for=""></label>
              <select class="form-control" name="nombre_bd">
                @forelse ($database as $item)
                    <option value="{{$item}}">{{$item}}</option>  
                @empty
                @endforelse
            </select>
            </div>
            <div class="form-group"><button type="submit" class="btn btn-primary">BUSCAR</button></div>
        </form>
    </div>

</div>
@endsection