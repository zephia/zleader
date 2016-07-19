@extends('ZLeader::layouts.master')

@section('title')
Areas - @parent
@stop

@section('page_header', 'Areas')

@section('content')
@include('CrudeCRUD::start')
@stop