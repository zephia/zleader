@extends('ZLeader::layouts.master')

@section('title')
Formularios - @parent
@stop

@section('scripts')
<!-- AdminLTE App -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>
@stop

@section('page_header', 'Formularios')

@section('content')
@include('CrudeCRUD::start')
@stop