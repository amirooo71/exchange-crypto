<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('app-assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('app-assets/img/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"/>
    <!-- CSS Files -->
    <link href="{{asset('app-assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('app-assets/css/now-ui-kit.css?v=1.1.0')}}" rel="stylesheet"/>
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('app-assets/css/demo.css')}}" rel="stylesheet"/>
    <!-- Styles -->

</head>

<body class="landing-page sidebar-collapse">

@include('layouts.shared.app-navbar')

<div class="wrapper">

    @include('layouts.shared.app-header')

    <div class="main">

        <div class="section section-images">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="hero-images-container">
                            <img src="{{asset('app-assets/img/hero-image-1.png')}}" alt="">
                        </div>
                        <div class="hero-images-container-1">
                            <img src="{{asset('app-assets/img/hero-image-2.png')}}" alt="">
                        </div>
                        <div class="hero-images-container-2">
                            <img src="{{asset('app-assets/img/hero-image-3.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper">
            @yield('body')
        </div>

    </div>

    @include('layouts.shared.app-footer')

</div>
</body>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- /Scripts -->
<!--   Core JS Files   -->
<script src="{{asset('app-assets/js/core/jquery.3.2.1.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/core/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('app-assets/js/core/bootstrap.min.js')}}" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="{{asset('app-assets/js/plugins/bootstrap-switch.js')}}"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="{{asset('app-assets/js/plugins/nouislider.min.js')}}" type="text/javascript"></script>
<!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
<script src="{{asset('app-assets/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
<!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
<script src="{{asset('app-assets/js/now-ui-kit.js?v=1.1.0')}}" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function () {
        // the body of this function is in assets/js/now-ui-kit.js
        nowuiKit.initSliders();
    });

    function scrollToDownload() {

        if ($('.section-download').length != 0) {
            $("html, body").animate({
                scrollTop: $('.section-download').offset().top
            }, 1000);
        }
    }

</script>

</html>