@extends('ZLeader::layouts.master')

@section('title')
Campos - @parent
@stop

@section('scripts')
<!-- AdminLTE App -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>
@stop

@section('page_header', 'Campos')

@section('content')
@include('CrudeCRUD::start')
@stop