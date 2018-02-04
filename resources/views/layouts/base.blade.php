<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<title>{{ config('app.name', 'Laravel') }}</title>--}}
    <title>Trade</title>

    <!--App Core Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!--/App Core Styles -->

    <!-- Theme Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/core.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/colors.css')}}" rel="stylesheet" type="text/css">
    <!-- /Theme global stylesheets -->

    <!--App Custom Css -->
    <link href="{{asset('css/custom.css')}}" rel="stylesheet" type="text/css">
    <!--/App Custom Css -->
</head>
<body>

<div id="app">
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content">
                    @yield('body')
                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->
</div>

<!-- App Core Js -->
<script src="{{asset('js/app.js')}}"></script>
<!-- /App Core Js -->

<!-- ThemeCore JS files -->
<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>
<!-- /Theme core JS files -->

<!-- Theme JS files -->
<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>
<!-- /theme JS files -->

<script src="https://cdn.jsdelivr.net/npm/vue2-filters/dist/vue2-filters.min.js"></script>
</body>
</html>
