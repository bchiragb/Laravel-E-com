@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Change Password</h1></div>
              </div>
        </div>
        @php
            $namex = auth()->user()->name;
            $fname = explode(' ', $namex);
        @endphp
       
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="prFeatures">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
                	<div class="mb-4">
                        @push('scripts')
                        <script type="text/javascript"> 
                            @foreach ($errors->all() as $error)
                                flasher.info("{{$error}}");
                            @endforeach      
                        </script>
                        @endpush
                        
                        <form method="post" action="{{ route('savepass') }}" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">	
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password" name="current_password" id="CustomerPassword" class="">                        	
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="CustomerPassword">New Password</label>
                                        <input type="password" name="new_password" id="NewPassword" class="">                        	
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="submit" class="btn mb-3" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
               	</div>
            </div>
        </div>
    </div>
</div>

<style>
.feature i {
    font-size: 50px;
    float: left;
}
.prFeatures .details {
    margin-left: 70px;
}    
</style>

@endsection