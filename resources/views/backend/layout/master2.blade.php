<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin Area</title>
  <meta name="robots" content="noindex, nofollow" />

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bassets/modules/fontawesome/css/all.min.css') }}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('bassets/modules/jqvmap/dist/jqvmap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bassets/modules/weather-icon/css/weather-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bassets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bassets/modules/summernote/summernote-bs4.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('bassets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('bassets/css/components.css') }}">

</head>

<body>
  <div id="app">

    @yield('admin_body_content')

    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('bassets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/popper.js') }}"></script>
    <script src="{{ asset('bassets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('bassets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('bassets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('bassets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/chart.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('bassets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('bassets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('bassets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('bassets/js/page/index-0.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('bassets/js/scripts.js') }}"></script>
    <script src="{{ asset('bassets/js/custom.js') }}"></script>
</body>
</html>