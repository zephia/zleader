@extends('ZLeader::layouts.master')

@section('title')
Campos - @parent
@stop

@section('scripts')
<!-- jQuery 2.2.3 -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>
@stop

@section('page_header', 'Campos')

@section('content')
@include('CrudeCRUD::start')
@stop