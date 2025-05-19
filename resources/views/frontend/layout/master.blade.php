<!DOCTYPE html>
<html class="no-js" lang="en">
    @php //add class according to page
        $currentUrl = request()->url();
        $classnm = "page-template";
        $ccountx = count(explode('/', $currentUrl));
        if(request()->is('product/*')) {
            $classnm = "template-product";
        } else if(request()->is('category/*')) {
            $classnm = "template-collection";
        } else {
            $lastSegment = basename($currentUrl); 
            if($ccountx == 3){
                $classnm = "home";
            } else {
                $classnm = strtolower(str_replace('-', '', ucwords($lastSegment, '-')));  
                if($classnm == 'search') $classnm = 'searchdata';
                if($classnm == 'contactus') $classnm = 'contactus page-template';
                if(is_numeric($classnm)) $classnm = 'single-order-template';
            } 
        } //echo "<br>"; echo $classnm.'--'.$ccountx;
        //
        $currentUrl = url()->current();
        $parsedUrl = parse_url($currentUrl);
        if(isset($parsedUrl['path'])) { $path = $parsedUrl['path']; } else { $path = $currentUrl; }
        $seo = \App\Models\Seo::where('url', $currentUrl)->orwhere('url', $path)->first();
    @endphp
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ $seo['title'] ?? 'Estore - Online E-commerce' }}</title>
        <meta name="description" content="{{ $seo['desc'] ?? '' }}">
        <meta name="keywords" content="{{ $seo['keyword']  ?? '' }}">
        <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">        
        <!-- Open Graph for Social Media Sharing -->
        <meta property="og:title" content="{{ $seo['title'] ?? 'Estore - Online E-commerce' }}">
        <meta property="og:description" content="{{ $seo['desc'] ?? '' }}">
        <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
        <meta property="og:type" content="website">
        <!-- Twitter Card for Social Media Sharing -->
        <meta name="twitter:title" content="{{ $seo['title'] ?? 'Estore - Online E-commerce' }}">
        <meta name="twitter:description" content="{{ $seo['desc']  ?? '' }}">
        <meta name="twitter:url" content="{{ $seo['canonical'] ?? url()->current() }}">
        <meta name="twitter:card" content="summary_large_image">
        <!-- -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('fassets/images/favicon.png') }}" />
        <link rel="stylesheet" href="{{ asset('fassets/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('fassets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('fassets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('fassets/css/responsive.css') }}">
    </head>
    <body class="{{ $classnm }}">
        <div id="pre-loader">
            <img src="{{ asset('fassets/images/loader.gif') }}" alt="Loading..." />
        </div>
        <div class="pageWrapper">
            <input type="hidden" id="currency_sign" value="{{ $currency }}" />
            
            @include('frontend.layout.header')

            @yield('body_content')

            @include('frontend.layout.footer')
            
            <!-- Including Jquery -->
            <script src="{{ asset('fassets/js/vendor/jquery-3.3.1.min.js') }}"></script>
            <script src="{{ asset('fassets/js/vendor/jquery.cookie.js') }}"></script>
            <script src="{{ asset('fassets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
            <script src="{{ asset('fassets/js/vendor/wow.min.js') }}"></script>
            <!-- Including Javascript -->
            <script src="{{ asset('fassets/js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('fassets/js/plugins.js') }}"></script>
            <script src="{{ asset('fassets/js/popper.min.js') }}"></script>
            <script src="{{ asset('fassets/js/lazysizes.js') }}"></script>
            <script src="{{ asset('fassets/js/main.js') }}"></script>
            <!--  -->
            <link rel="stylesheet" href="{{ asset('bassets/modules/fontawesome/css/all.min.css') }}">
            
            <link rel="stylesheet" href="{{ asset('fassets/css/custom.css') }}">
            <script src="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.min.js"></script>
            <link rel="stylesheet" href="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.css" />

            @stack('scripts')
            <script>
                $(document).ready(function(){ 
                    $(document).on('click', '.rmove_itm', function(){
                        var idx = $(this).attr('data');
                        $.ajax({
                            method: "POST",
                            url: "{{ route('removeitem') }}",
                            data: { _token: "{{ csrf_token() }}", pid: idx },
                            success: function(data){
                                if(data.status == "success"){
                                    flasher.success(data.message);
                                    //$("#CartCount").html(data.qty);
                                    //$(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                                    //$('#header-cart').html('');
                                    //$('#header-cart').html('<ul class="mini-products-list"><div class="nopro">Cart is empty.</div></ul>');
                                    //$(".pro_"+idx).remove();
                                    //$(".product_"+idx).remove();
                                    $('#header-cart').html('');
                                    $('#header-cart').html(data.html);
                                }
                            },
                            error: function(data){ console.log(data); }
                        });
                    });
                    //
                    $(document).on('click', '#subscribe', function(){
                        var email = $("#subscribe_email").val();
                        if(email){
                            $("#subscribe_email").css('border', '1px solid #d7d7d7');
                            $.ajax({
                                method: "POST",
                                url: "{{ route('subscribe') }}",
                                data: { _token: "{{ csrf_token() }}", email: email },
                                success: function(data){
                                    if(data.status == "success"){
                                        flasher.success(data.message);
                                    } else {
                                        flasher.warning(data.message);
                                    }
                                    $("#subscribe_email").val('');
                                },
                                error: function(data){ console.log(data); }
                            });
                        } else {
                            $("#subscribe_email").css('border', '1px solid red');
                        }
                    }); 
                });
                //
                jQuery(document).ready(function(){  
                    // jQuery('.closepopup').on('click', function () {
                    // Get the current count of popup displays from localStorage (or default to 0 if not found)
                    var popupCounter = localStorage.getItem('popupCounter') ? parseInt(localStorage.getItem('popupCounter')) : 0;
                    function showPopup() {
                        var countx = jQuery("#pop_count").val();
                        if (popupCounter <= countx) {  
                            $('#popupModal').fadeIn();
                            popupCounter++;  
                            localStorage.setItem('popupCounter', popupCounter); // Store the updated counter value in localStorage
                        }
                    }
                    showPopup();
                    $('.close').on('click', function() { $('#popupModal').fadeOut(); });
                    $(window).on('click', function(event) {
                        if ($(event.target).is('#popupModal')) { $('#popupModal').fadeOut(); }
                    });
                }); 
            </script>
        </div>
    </body>
</html>