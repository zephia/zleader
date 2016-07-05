<!DOCTYPE html>
<html>
	<head>
		<title>
			@section('title')
			Data-Grid Demo
			@show
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="{{ URL::to('vendor/ZLeader/assets/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
		<link href="{{ URL::to('vendor/ZLeader/assets/css/font-awesome.min.css') }}" rel="stylesheet" media="screen">
		<link href="{{ URL::to('vendor/ZLeader/assets/css/demo.css') }}" rel="stylesheet" media="screen">

		@yield('styles')

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			@yield('content')
		</div>

		<script src="{{ URL::to('vendor/ZLeader/assets/js/jquery.min.js') }}"></script>
		<script src="{{ URL::to('vendor/ZLeader/assets/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('vendor/ZLeader/cartalyst/data-grid/js/underscore.js') }}"></script>
		<script src="{{ URL::asset('vendor/ZLeader/cartalyst/data-grid/js/data-grid.js') }}"></script>

		<script type="text/javascript">
			$('.tip').tooltip();
		</script>

		@yield('scripts')
	</body>
</html>