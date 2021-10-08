<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <title>@lang('Logo') | @lang('Title')</title>
        @stack('meta')

        <!-- CSS -->
        <link rel="stylesheet" href="{{asset('site-assets/bootstrap-3.3.7/css/bootstrap.min.css')}}" >
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha256-T/zFmO5s/0aSwc6ics2KLxlfbewyRz6UNw1s3Ppf5gE=" crossorigin="anonymous">-->

        <link rel="stylesheet" href="{{asset('site-assets/css/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('site-assets/css/icofont/css/icofont.css')}}">
        <link rel="stylesheet" href="{{asset('site-assets/css/owl.carousel.css')}}">
        <link rel="stylesheet" href="{{asset('site-assets/css/slidr.css')}}">
        <link rel="stylesheet" href="{{asset('site-assets/css/main.css')}}">
        <link id="preset" rel="stylesheet" href="{{asset('site-assets/css/presets/preset1.css')}}">
        <link rel="stylesheet" href="{{asset('site-assets/css/responsive.css')}}">


        <!-- font -->
        <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,300' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Signika+Negative:400,300,600,700' rel='stylesheet' type='text/css'>
        <link href="{{asset('site-assets/css/mukti/font.css')}}" rel="stylesheet">

        <!-- icons -->
        <link rel="icon" href="{{asset('site-assets/logo/favicon.ico')}}">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('site-assets/images/ico/apple-touch-icon-144-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('site-assets/images/ico/apple-touch-icon-114-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('site-assets/images/ico/apple-touch-icon-72-precomposed.html')}}">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('site-assets/images/ico/apple-touch-icon-57-precomposed.png')}}">
        <!-- icons -->


        <!-- JS -->
        <script src="{{asset('site-assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('site-assets/js/modernizr.min.js')}}"></script>
        <script src="{{asset('site-assets/bootstrap-3.3.7/js/bootstrap.min.js')}}"></script>
        <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha256-7dA7lq5P94hkBsWdff7qobYkp9ope/L5LQy2t/ljPLo=" crossorigin="anonymous"></script>-->

        <!-- animate css -->
        <link rel="stylesheet" href="{{asset('site-assets/css/animate.css')}}">

        <!-- Chosen -->
        <link rel="stylesheet" href="{{asset('site-assets/plugins/chosen/chosen.min.css')}}">
        <script src="{{asset('site-assets/plugins/chosen/chosen.jquery.min.js')}}"></script>

        <!-- notify -->
        <script src="{{asset('site-assets/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

        <!-- bootbox -->
        <script src="{{asset('site-assets/plugins/bootbox.min.js')}}"></script>


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--stack styles-->
        @stack('styles')
        <!--stack styles-->
    </head>
    <body class="bn">
        <!-- header -->
        <header id="header" class="clearfix">
            <!-- navbar -->
            <nav class="navbar navbar-default">
                <div class="container">
                    <!-- navbar-header -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">@lang('Toggle navigation')</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{url('/')}}"><img class="img-responsive logo" src="{{asset('site-assets/logo/large.png')}}" alt="Logo" ></a>
                    </div>
                    <!-- /navbar-header -->

                    @include('site.common.topmenu')

                </div><!-- container -->
            </nav><!-- navbar -->
        </header><!-- header -->


        <!-- notification -->
        @yield('notification')
        <!-- notification -->

        <!--site content-->
        @yield('siteContent')
        <!--site content-->

        @stack('modals')

        @include('site.common.footer')

        <!--
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="{{asset('site-assets/js/gmaps.min.js')}}"></script>
        -->

        <script src="{{asset('site-assets/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('site-assets/js/scrollup.min.js')}}"></script>
        <script src="{{asset('site-assets/js/price-range.js')}}"></script>
        <script src="{{asset('site-assets/js/jquery.countdown.js')}}"></script>
        <script src="{{asset('site-assets/js/custom.js')}}"></script>

        <!--stack scripts-->
        @stack('scripts')
        <!--stack scripts-->
    </body>
</html>

