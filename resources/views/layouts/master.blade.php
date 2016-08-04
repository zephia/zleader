<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'zLeader | Lead router and dashboard')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/css/skins/skin-red.min.css') }}">

  @yield('styles')

  <style type="text/css">
    #crudeContainer .container{
      width: auto;
    }
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{
      background-color: #DD4B39!important;
      border-color:#DD4B39!important;
    }
    .content-wrapper .crude-box .crude-header .crude-header-title{
      background-color: #DD4B39!important;
    }
    .main-footer a{
      color: #DD4B39;
    }
    #leadShow .modal-body .row {
      margin: 0 -5px;
    }
    #leadShow .modal-body .row > div{
      padding: 0 5px;
    }
    .loader{
       position: fixed;
       z-index: 99;
       left:50%;
       top: 50%;
       display: none;
    }
    .loader.loader-active{
       display: block !important;
    }
    .loader span{
      left:50%;
      z-index: 999;
      width: 200px;
      margin:auto;
      background-color: #DD4B39;
      font-size: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      padding: 30px;
      display: block;
      text-align: center;
      color: white;
    }
    .loader span:after{
      content: "Cargando...";
    }
    .table-leads tr{
      cursor: pointer;
    }
    .widget-user-2 .widget-user-image>img{
      border-radius: 0;
    }
    .widget-company-count{
      min-height: 266px;
    }
    .small-box .icon{
      top: 10px;
    }
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><i class="fa fa-crosshairs"></i> <b>z</b>L</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><i class="fa fa-crosshairs"></i> <b>z</b>Leader</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      @if($app_bindings = app()->getBindings() && empty($app_bindings['user']))
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ app('user')->avatar_url }}" class="img-circle" height="160" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ app('user')->first_name . ' ' . app('user')->last_name }}</p>
          <!-- Status -->
          <a href="#">{{ app('user')->roles[0]->name }}</a>
        </div>
        <div class="pull-right text-right">
          <a href="/logout" title="Cerrar sesión"><i class="fa fa-sign-out"></i></a>
        </div>
      </div>
      @endif
      @include('ZLeader::layouts.menu')
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('page_header')
        <small>@yield('page_header_description')</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      by Zephia Digital Mind
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; {{ date("Y", time()) }} <a href="#">Autocity</a>.</strong> All rights reserved.
  </footer>
</div>
@yield('scripts')
</body>
</html>
