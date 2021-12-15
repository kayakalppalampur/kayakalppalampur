<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <title>{{ config('app.name', 'Kayakalp') }}</title>
    <link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/bootstrap-datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/animate.css') }} " rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery.steps.css') }}" rel="stylesheet" type="text/css" />
    @yield('css')
    <script>
        window.Laravel = <?php echo  json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>