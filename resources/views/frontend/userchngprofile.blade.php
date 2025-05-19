@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Change profile details</h1></div>
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
                        <form method="post" action="{{ route('saveprofile') }}" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">	
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="first_name" id="FirstName" autofocus="" value="{{ $fname[0] }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="last_name" id="LastName" autofocus="" value="{{ $fname[1] }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="contact" id="contact" autofocus="" value="{{ auth()->user()->contact; }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="CustomerEmail">Email</label>
                                        <input type="email" name="email" id="CustomerEmail" class=""  value="{{ auth()->user()->email; }}" autocorrect="off" autocapitalize="off" autofocus="off" disabled>
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