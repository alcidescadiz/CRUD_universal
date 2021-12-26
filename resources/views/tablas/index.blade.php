@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">

    
@if (session()->has('message'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('message') }} 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

    <x-tablas.buscar :tablas="$tablas" :database="$database" />

</div>
@endsection