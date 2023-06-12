<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

</head>

<body>
    @yield('content')
    <script src="{{ asset('js/Chart.min.js') }}" defer></script>
    <script>
        $(document).ready(function(){
        $(document).on('change','#matricule', function(){
            // console.log('yay');
            var matricule = $(this).val();
            // console.log(matricule);

            $.ajax({
                type: 'get',
                url: '{!!URL::to("findEmploye")!!}',
                data: {'matricule':matricule},
                success: function(data){
                    $('#nom').val(data.nom);
                    $('#prenom').val(data.prenom);
                },
                error: function(){

                }

            });
        });
    });
    </script>

</body>

</html>
