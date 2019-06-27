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
    .main-header .logo .logo-mini, .main-header .logo .logo-lg{
      padding-top:7px;
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
    <a href="{{ action('\Zephia\ZLeader\Http\Controllers\DashboardController@index') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
        <g>
            <path fill="#FFFFFF" d="M28.967,16.119h-1.426c-0.303,0-0.583-0.115-0.792-0.326c-0.208-0.21-0.323-0.489-0.323-0.791
                c0-0.304,0.115-0.585,0.323-0.792c0.209-0.216,0.489-0.328,0.792-0.328h1.426H30C29.454,6.465,23.536,0.546,16.118,0v1.054V2.48
                c0,0.304-0.112,0.584-0.329,0.793c-0.206,0.209-0.486,0.323-0.791,0.323c-0.303,0-0.582-0.115-0.792-0.323
                c-0.211-0.208-0.326-0.489-0.326-0.793V1.054V0C6.464,0.546,0.546,6.465,0,13.882h1.033h1.425c0.304,0,0.584,0.112,0.793,0.328
                c0.207,0.207,0.323,0.488,0.323,0.792c0,0.303-0.116,0.582-0.323,0.791c-0.209,0.211-0.489,0.326-0.793,0.326H1.033H0
                C0.546,23.536,6.464,29.455,13.881,30v-1.055v-1.425c0-0.302,0.115-0.583,0.326-0.793c0.21-0.207,0.488-0.323,0.792-0.323
                c0.304,0,0.584,0.116,0.791,0.323c0.217,0.21,0.329,0.491,0.329,0.793v1.425V30C23.536,29.455,29.454,23.536,30,16.119H28.967z"/>
            <path fill="#D73925" d="M6.743,12.158c-0.304,0-0.581-0.12-0.792-0.326c-0.211-0.207-0.325-0.49-0.325-0.791
                c0-0.305,0.115-0.584,0.325-0.794c0.211-0.213,0.487-0.324,0.792-0.324h15.399c0.627,0,1.14,0.21,1.588,0.648
                c0.44,0.447,0.644,0.953,0.644,1.586c0,0.535-0.155,0.975-0.462,1.371c-0.307,0.4-0.7,0.671-1.19,0.794l-13.068,3.1
                c-0.373,0.091-0.564,0.185-0.564,0.255c0,0.113,0.327,0.162,1.006,0.162h13.162c0.302,0,0.583,0.112,0.792,0.329
                c0.208,0.207,0.324,0.487,0.324,0.792c0,0.303-0.116,0.582-0.324,0.792c-0.209,0.211-0.49,0.326-0.792,0.326H7.859
                c-0.629,0-1.14-0.212-1.586-0.655c-0.437-0.442-0.648-0.949-0.648-1.583c0-0.489,0.137-0.931,0.394-1.327
                c0.254-0.398,0.627-0.653,1.072-0.772l13.279-3.121c0.256-0.064,0.37-0.16,0.37-0.232c0-0.159-0.279-0.231-0.835-0.231H6.743
                L6.743,12.158z"/>
        </g>
        </svg>
      </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
        <g>
            <path fill="#FFFFFF" d="M28.967,16.119h-1.426c-0.303,0-0.583-0.115-0.792-0.326c-0.208-0.21-0.323-0.489-0.323-0.791
                c0-0.304,0.115-0.585,0.323-0.792c0.209-0.216,0.489-0.328,0.792-0.328h1.426H30C29.454,6.465,23.536,0.546,16.118,0v1.054V2.48
                c0,0.304-0.112,0.584-0.329,0.793c-0.206,0.209-0.486,0.323-0.791,0.323c-0.303,0-0.582-0.115-0.792-0.323
                c-0.211-0.208-0.326-0.489-0.326-0.793V1.054V0C6.464,0.546,0.546,6.465,0,13.882h1.033h1.425c0.304,0,0.584,0.112,0.793,0.328
                c0.207,0.207,0.323,0.488,0.323,0.792c0,0.303-0.116,0.582-0.323,0.791c-0.209,0.211-0.489,0.326-0.793,0.326H1.033H0
                C0.546,23.536,6.464,29.455,13.881,30v-1.055v-1.425c0-0.302,0.115-0.583,0.326-0.793c0.21-0.207,0.488-0.323,0.792-0.323
                c0.304,0,0.584,0.116,0.791,0.323c0.217,0.21,0.329,0.491,0.329,0.793v1.425V30C23.536,29.455,29.454,23.536,30,16.119H28.967z"/>
            <path fill="#D73925" d="M6.743,12.158c-0.304,0-0.581-0.12-0.792-0.326c-0.211-0.207-0.325-0.49-0.325-0.791
                c0-0.305,0.115-0.584,0.325-0.794c0.211-0.213,0.487-0.324,0.792-0.324h15.399c0.627,0,1.14,0.21,1.588,0.648
                c0.44,0.447,0.644,0.953,0.644,1.586c0,0.535-0.155,0.975-0.462,1.371c-0.307,0.4-0.7,0.671-1.19,0.794l-13.068,3.1
                c-0.373,0.091-0.564,0.185-0.564,0.255c0,0.113,0.327,0.162,1.006,0.162h13.162c0.302,0,0.583,0.112,0.792,0.329
                c0.208,0.207,0.324,0.487,0.324,0.792c0,0.303-0.116,0.582-0.324,0.792c-0.209,0.211-0.49,0.326-0.792,0.326H7.859
                c-0.629,0-1.14-0.212-1.586-0.655c-0.437-0.442-0.648-0.949-0.648-1.583c0-0.489,0.137-0.931,0.394-1.327
                c0.254-0.398,0.627-0.653,1.072-0.772l13.279-3.121c0.256-0.064,0.37-0.16,0.37-0.232c0-0.159-0.279-0.231-0.835-0.231H6.743
                L6.743,12.158z"/>
        </g>
        </svg>
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="69px" height="30px" viewBox="0 0 69 30" enable-background="new 0 0 69 30" xml:space="preserve">
        <g>
            <g>
                <path fill="#FFFFFF" d="M3.569,21.835H0V4.13h3.569V21.835z"/>
                <path fill="#FFFFFF" d="M17.083,19.974c-1.443,1.369-3.137,2.052-5.08,2.052s-3.549-0.6-4.818-1.802
                    c-1.269-1.201-1.903-2.812-1.903-4.832c0-2.02,0.647-3.627,1.939-4.82c1.292-1.193,2.82-1.79,4.58-1.79
                    c1.761,0,3.256,0.533,4.485,1.599c1.229,1.066,1.844,2.53,1.844,4.39v1.909H8.803c0.111,0.701,0.476,1.273,1.094,1.718
                    c0.619,0.445,1.317,0.668,2.094,0.668c1.253,0,2.285-0.422,3.093-1.264L17.083,19.974z M13.656,12.362
                    c-0.492-0.414-1.091-0.621-1.796-0.621c-0.707,0-1.353,0.215-1.94,0.644c-0.586,0.43-0.927,1.011-1.023,1.742h5.615
                    C14.433,13.364,14.148,12.776,13.656,12.362z"/>
                <path fill="#FFFFFF" d="M30.621,21.835h-3.355v-1.551c-0.92,1.161-2.058,1.742-3.415,1.742c-1.356,0-2.494-0.394-3.414-1.181
                    c-0.92-0.788-1.38-1.837-1.38-3.149c0-1.313,0.476-2.295,1.428-2.947c0.952-0.652,2.253-0.978,3.903-0.978h2.665v-0.072
                    c0-1.368-0.722-2.052-2.165-2.052c-0.619,0-1.274,0.124-1.963,0.37c-0.691,0.247-1.274,0.553-1.749,0.919l-1.594-2.315
                    c1.682-1.225,3.601-1.838,5.758-1.838c1.554,0,2.823,0.39,3.807,1.17c0.983,0.779,1.475,2.013,1.475,3.698V21.835z M27.029,16.944
                    v-0.621h-2.236c-1.428,0-2.142,0.446-2.142,1.337c0,0.462,0.171,0.815,0.512,1.062c0.341,0.247,0.828,0.37,1.463,0.37
                    c0.634,0,1.193-0.195,1.677-0.585C26.787,18.118,27.029,17.596,27.029,16.944z"/>
                <path fill="#FFFFFF" d="M38.057,22.026c-1.515,0-2.868-0.645-4.057-1.933c-1.19-1.288-1.785-2.887-1.785-4.796
                    c0-1.909,0.58-3.472,1.737-4.689c1.158-1.217,2.514-1.826,4.069-1.826c1.555,0,2.823,0.55,3.807,1.647V4.13h3.57v17.705h-3.57
                    v-1.694C40.828,21.398,39.571,22.026,38.057,22.026z M35.808,15.44c0,1.035,0.309,1.881,0.928,2.541
                    c0.618,0.66,1.34,0.991,2.165,0.991c0.824,0,1.526-0.33,2.106-0.991c0.578-0.66,0.868-1.51,0.868-2.553
                    c0-1.042-0.289-1.909-0.868-2.601c-0.58-0.692-1.289-1.038-2.13-1.038c-0.841,0-1.563,0.35-2.165,1.05
                    C36.109,13.54,35.808,14.407,35.808,15.44z"/>
                <path fill="#FFFFFF" d="M58.911,19.974c-1.443,1.369-3.137,2.052-5.08,2.052c-1.943,0-3.549-0.6-4.819-1.802
                    c-1.269-1.201-1.903-2.812-1.903-4.832c0-2.02,0.647-3.627,1.94-4.82c1.292-1.193,2.819-1.79,4.58-1.79s3.256,0.533,4.485,1.599
                    c1.229,1.066,1.844,2.53,1.844,4.39v1.909H50.63c0.112,0.701,0.477,1.273,1.095,1.718c0.619,0.445,1.316,0.668,2.094,0.668
                    c1.253,0,2.285-0.422,3.093-1.264L58.911,19.974z M55.485,12.362c-0.491-0.414-1.091-0.621-1.797-0.621
                    c-0.706,0-1.352,0.215-1.939,0.644c-0.586,0.43-0.928,1.011-1.023,1.742h5.615C56.262,13.364,55.976,12.776,55.485,12.362z"/>
                <path fill="#FFFFFF" d="M68.31,12.147c-1.063,0-1.856,0.378-2.379,1.133c-0.524,0.756-0.785,1.754-0.785,2.996v5.559h-3.57V8.998
                    h3.57v1.694c0.459-0.525,1.035-0.97,1.725-1.336c0.69-0.366,1.392-0.557,2.106-0.573L69,12.147H68.31z"/>
            </g>
        </g>
        </svg>
      </span>
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
      @if(Auth::check())
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ Auth::user()->avatar_url }}" class="img-circle" height="160" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</p>
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
