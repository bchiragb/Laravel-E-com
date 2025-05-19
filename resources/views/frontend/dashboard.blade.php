@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Dashboard</h1></div>
              </div>
        </div>
       
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="related-product grid-products">
                <header class="section-header">
                    @php
                        $namex = auth()->user()->name;
                        $fname = explode(' ', $namex);
                    @endphp
                    <h2 class="section-header__title text-centerx h2"><span>Welcome Back, {{ $fname[0] }}</span></h2>
                </header>    
            </div>
            <div class="prFeatures">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-book"></i>
                        <div class="details"><h3><a href="{{ route('order') }}">Check your order</a></h3>Get real-time updates on your orders and delivery progress.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-truck"></i>
                        <div class="details"><h3><a href="{{ route('shipping') }}">Check your shipping</a></h3>Check the progress of your orders and know when they'll arrive.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-feather"></i>
                        <div class="details"><h3><a href="">Browse our collections</a></h3>Find new arrivals and trending products tailored just for you.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-star"></i>
                        <div class="details"><h3><a href="">Exclusive Deals</a></h3>Unlock special discounts and offers available only to you.</div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-id-badge"></i>
                        <div class="details"><h3><a href="{{ route('change-profile') }}">Edit Profile</a></h3>Update your details for a personalized experience.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-key"></i>
                        <div class="details"><h3><a href="{{ route('change-password') }}">Change password</a></h3>Secure your account by updating password.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-address-card"></i>
                        <div class="details"><h3><a href="{{ route('address') }}">Edit Address</a></h3>Make your address are current for smooth and fast shipping.</div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3 feature">
                        <i class="iconq fa fa-solid fa-heart"></i>
                        <div class="details"><h3><a href="{{ route('wishlist.index') }}">Wishlist</a></h3>Customize your preferences for product recommendations.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="btn my-sm-5" href="{{ route('logout') }}">Logout</a>
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