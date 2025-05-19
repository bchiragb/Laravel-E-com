<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Admin Area</title>
  <meta name="_token" content="{{ csrf_token() }}">
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

    @include('backend.layout.header')
   
    @yield('admin_body_content')

    @include('backend.layout.footer')
   
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
    <script src="{{ asset('bassets/js/myjs.js') }}"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
     --}}
    <link rel="stylesheet" href="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <link href="{{ asset('bassets/css/custom.css') }}" rel="stylesheet"/>
   
    @stack('scripts')

    {{ $scripts ?? '' }}

    @if($errors->any())
      <script src="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.min.js"></script>
    @endif

    <script type="text/javascript">
      @if($errors->any())
        @foreach ($errors->all() as $error)
          flasher.error("{{$error}}")
          //toastr.error("{{$error}}")
        @endforeach
      @endif

      $(document).ready(function(){
        $.ajaxSetup({
          headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
        });

        //change sts
        $('body').on('click', '.chg_sts', function(){
            let ischk = $(this).is(':checked');
            let id = $(this).attr('data-val');
            let urlx = $("#stsurl").val();
            $.getScript("https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.min.js");
    
            $.ajax({
              url: urlx,
              method: 'post',
              data: {
                _token: "{{ csrf_token() }}",
                status: ischk,
                id: id
              },
              success: function(data){
                flasher.success(data.message);
              },
              error: function(xhr, status, error){
                console.log(error);
              }
            });
        });

        //delete
        $('body').on('click', '.delete_item', function(event){
          event.preventDefault();
          let deleteurl = $(this).attr('href');
          let rload = $("#rloadurl").val();
          $.getScript("https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.min.js");

          Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                type: 'DELETE',
                url: deleteurl,
                //_token: "{{ csrf_token() }}",
                success: function(data){
                  if(data.status == "success") {
                    Swal.fire({
                      title: "Deleted!",
                      text: data.message,
                      icon: "success"
                    });
                    setTimeout(function () {
                      window.location.href = rload;
                    }, 1000);
                    
                  } else if(data.status == "error"){
                    Swal.fire({
                      title: "Error!",
                      text: data.message,
                      icon: "error"
                    });
                  }
                },
                error: function(xhr, status, error){
                  console.log(error);
                }
              });
            }
          });
        });
      });
    </script>

</body>
</html>