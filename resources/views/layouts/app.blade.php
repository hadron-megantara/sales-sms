<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sales - System') }}</title>

    <link rel="shortcut icon" type="image/png" href="/img/icon/icon.png"/>

    <!-- Scripts -->
        <script src="/js/jquery-1.12.4.min.js"></script>
        <script src="/js/app.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/jquery.dataTables.min.js"></script>
        <script src="/js/jquery.priceformat.min.js"></script>
        <script src="/js/highstock.js"></script>
        <script src="/js/spin.min.js"></script>
        <script src="/js/jquery.uploadfile.min.js"></script>
    <!-- End Scripts -->

    <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- End Fonts -->

    <!-- Styles -->
        <link href="/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/header.css" rel="stylesheet">
        <link href="/css/footer.css" rel="stylesheet">
        <link href="/css/jquery-ui.min.css" rel="stylesheet">
        <link href="/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="/css/highcharts.css" rel="stylesheet">

        <!-- BEGIN PLUGIN CSS -->
        <link href="/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/animate.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" />
        <link href="/css/uploadfile.css" rel="stylesheet">
        <!-- END PLUGIN CSS -->

        @if(session('user'))
            <!-- BEGIN CORE CSS FRAMEWORK -->
            <link href="/css/webarch.css" rel="stylesheet" type="text/css" />
            <!-- END CORE CSS FRAMEWORK -->
        @else
            <link href="/css/login.css" rel="stylesheet" type="text/css" />
        @endif

        <link href="/css/style.css" rel="stylesheet">
    <!-- End Styles -->
</head>
<body>
    @include('includes.header')
    @yield('content')
    @include('includes.footer')

    <!-- BEGIN CORE JS FRAMEWORK-->
    <script src="/plugins/pace/pace.min.js" type="text/javascript"></script>
    <!-- END CORE JS FRAMEWORK -->

    <!-- BEGIN JS DEPENDECENCIES-->
    <script src="/plugins/jquery-block-ui/jqueryblockui.min.js" type="text/javascript"></script>
    <script src="/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <script src="/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <!-- END CORE JS DEPENDECENCIES-->

    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="/js/webarch.js" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
</body>
</html>
