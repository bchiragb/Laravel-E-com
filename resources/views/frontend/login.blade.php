@extends('frontend.layout.master')

@section('body_content')

<!--Body Content-->
<div id="page-content">
    <!--Page Title-->
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Login</h1></div>
          </div>
    </div>
    <!--End Page Title-->
    

    {{-- @if (count($errors) > 0)
        <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif --}}


    {{-- @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif --}}

    @push('scripts')
    <script type="text/javascript"> 
        @foreach ($errors->all() as $error)
            flasher.error("{{$error}}");
        @endforeach      
    </script>
    @endpush

    @if(session('status'))
        <script type="text/javascript"> 
            @foreach ($errors->all() as $error)
                flasher.error("{{$error}}");
            @endforeach      
        </script>
    @endif

    


    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
                <div class="mb-4">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('post_login') }}" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">	
                        @csrf 
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="CustomerEmail">Email</label>
                                    <input type="email" name="customer_email" required id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="">
                                    @if ($errors->has('customer_email')) <p style="color:red;">{{ $errors->first('customer_email') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="CustomerPassword">Password</label>
                                    <input type="password" required name="customer_password" placeholder="" id="CustomerPassword" class="">  
                                    @if ($errors->has('customer_password')) <p style="color:red;">{{ $errors->first('customer_password') }}</p> @endif                      	
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="submit" class="btn mb-3" value="Sign In">
                                <p class="mb-4">
                                    <a href="#" id="RecoverPassword">Forgot your password?</a> &nbsp; | &nbsp;
                                    <a href="{{ route('register') }}" id="customer_register_link">Create account</a>
                                </p>
                            </div>
                        </div>
                    </form>
                    <form method="post" action="{{ route('post_login') }}" id="forgotpass" accept-charset="UTF-8" class="contact-formx">	
                    @csrf 
                    <div class="forgotpass">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="resetemailid">Email</label>
                                    <input type="email" name="customer_email" required id="resetemailid" class="" autocorrect="off" autocapitalize="off" autofocus="">
                                    @if ($errors->has('customer_email')) <p style="color:red;">{{ $errors->first('customer_email') }}</p> @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="submit" class="btn mb-3 resetpass" value="Reset Password">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>        
</div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){ 
            $('.forgotpass').hide();
            $('#RecoverPassword').on('click', function(){
                $('.forgotpass').toggle();
            });
            //
            $('#forgotpass').on('submit', function(e){
                e.preventDefault();
                var emailx = $("#resetemailid").val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('forgotpassword') }}",
                    data: { _token: "{{ csrf_token() }}", email: emailx },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            //$("#CartCount").html(data.qty);
                            //$(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                            //$('#header-cart').html('');
                            //$('#header-cart').html('<ul class="mini-products-list"><div class="nopro">Cart is empty.</div></ul>');
                            //$(".pro_"+idx).remove();
                            //$(".product_"+idx).remove();
                        } else {
                            flasher.warning(data.message);
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });

        });
    </script>
@endpush()