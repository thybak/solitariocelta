<!DOCTYPE html>
<html>
<head>
    <title>Solitario Celta - @yield('titulo')</title>
    <link href="{{ URL::asset('css/semantic.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('css/estilos.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('css/datepicker.min.css') }}" rel="stylesheet"/>
    <script src="{{ URL::asset('js/jquery-3.2.0.min.js') }}"></script>
    <script src="{{ URL::asset('js/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/semantic.min.js') }}"></script>
    <script src="{{ URL::asset('js/frontEnd.js') }}"></script>
</head>
<body>
@yield('menu')
@yield('cuerpo')


</body>
</html>