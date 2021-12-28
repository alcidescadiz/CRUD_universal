@extends('layouts.dtshow')
@section('title', __('Dashboard'))

@section('css')

<script src="{{ asset('js/jquery.slim.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>  
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">


@endsection

@section('content')

<div class="container">
    
    @if (session()->has('message'))
        <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
            {{ session('message') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <x-tablas.buscar :tablas="$tablas"  :nombre="$nombre" :database="$database" />

    <x-tablas.tabla :tablas="$tablas" :nombre="$nombre" :database="$database" :header="$header" :body="$body" :campos="$campos"  />

</div>

@endsection
