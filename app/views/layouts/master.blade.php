<!doctype html>
<html lang="en">
<head>
	@section('header')
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!--    JQUERY      -->
    <link rel="stylesheet" href="{{ URL::asset('styles/start/jquery-ui-1.10.4.custom.min.css') }}"  media="screen" />
    
    <!--    BOOTSTRAP   -->
	<link rel="stylesheet" href="{{ URL::asset('styles/bootstrap.min.css') }}"  media="screen">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css"/> </link>
    
    <!--    FONT AWESOME   -->
    <link rel="stylesheet" href="{{ URL::asset('/styles/font-awesome/css/font-awesome.css'); }}">
    
    <!--    MAIN STYLE  -->
    <link rel="stylesheet" href="{{ URL::asset('styles/custom.css') }}" media="screen"></link>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            font-family: "Calibri";
        }
    </style>
    
    @if (isset(Auth::user()->user_type))
        @if (2 == Auth::user()->user_type)
        <style>
            .main_container .top_nav {
                margin-left:230px;
            }
            
            .nav-md .container.body .right_col {
                margin-left:230px;
            }
            
            @media (min-width: 992px) {
                footer {
                    margin-left: 230px;
                }
            }
        </style>
        @endif
    @endif

    @show
    
    @yield('style')
</head>
<body class="nav-md">
    
    <!-- MAIN CONTAINER -->
    <div class="container body">
        <div class="main_container">            
            @section('nav')
                @if (2 == Auth::user()->user_type)
                    @include('layouts.nav-left')
                @endif
                @include('layouts.nav-top')
            @show
            
            <div class="right_col" role="main">
                @yield('content')
            </div>
            
            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    Copyright&copy; {{ date('Y') }} Tank Level Tracker. All rights reserved
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>
	
    <!--	JQUERY      -->
    <script type="text/javascript"> var BaseURL = '{{ URL::to("/") }}'; </script>
    <script src="{{ URL::asset('scripts/1.11.2.jquery.min.js') }}"></script>
    <script src="{{ URL::asset('scripts/ui/minified/jquery-ui-1.10.4.custom.min.js') }}"></script>
    
    <!--    BOOTSTRAP   -->
    <script src="{{ URL::asset('scripts/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('scripts/select2.min.js') }}"></script>
    
    <!--    JQX CHARTS     -->
    <script src="{{ URL::asset('/scripts/jqwidgets/jqxcore.js') }}"></script>
    <script src="{{ URL::asset('/scripts/jqwidgets/jqxchart.js') }}"></script>
    <script src="{{ URL::asset('/scripts/jqwidgets/jqxgauge.js') }}"></script>
    
    <!--    HIGHCHARTS      -->
    <script src="{{ URL::asset('/Highcharts-4.1.4/js/highcharts.js') }}"></script>
	<script src="{{ URL::asset('/Highcharts-4.1.4/js/highcharts-more.js') }}"></script>
	
    <!--    CUSTOM          -->
    <script src="{{ URL::asset('scripts/custom.js') }}"></script>
    
    <!--	JAVASCRIPT BY PAGE  -->
    @yield('javascript')
</body>
</html>