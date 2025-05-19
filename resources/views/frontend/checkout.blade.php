@extends('frontend.layout.master')

@section('body_content')

@php
    $col_attr = \App\Models\SiteSetting::where('key', 'productsetting1')->first();
    $shipamt = $col_attr->val1;
@endphp

@if(Cart::content()->count() == 0) 
    <script type="text/javascript">
        //window.location.href = "{{ route('home') }}"; 
    </script>
@endif

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Checkout</h1></div>
          </div>
    </div>
    
    <div class="container">
        <div class="row">
            @if(!Auth::check())
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="customer-box returning-customer">
                        <h3><i class="icon anm anm-user-al"></i> Returning customer? <a href="#customer-login" id="customer" class="text-white text-decoration-underline" data-toggle="collapse">Click here to login</a></h3>
                        <div id="customer-login" class="collapse customer-content">
                            <div class="customer-info">
                                <form method="post" action="{{ route('post_login') }}" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">	
                                    @csrf 
                                    <div class="row">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <input type="email" name="customer_email" placeholder="Email *" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="">
                                            @if ($errors->has('customer_email')) <p style="color:red;">{{ $errors->first('customer_email') }}</p> @endif
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <input type="password" value="" name="customer_password" placeholder="Password *" id="CustomerPassword" class="">  
                                            @if ($errors->has('customer_password')) <p style="color:red;">{{ $errors->first('customer_password') }}</p> @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-check width-100 margin-20px-bottom">
                                                <a href="{{ route('login') }}" class="float-right">Forgot your password?</a>
                                            </div>
                                            <div class="form-checkx width-100 margin-20px-bottom">
                                                <button type="submit" class="btn btn-primary mt-3x">Sign In</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                <div class="customer-box customer-coupon">
                    <h3 class="font-15 xs-font-13"><i class="icon anm anm-gift-l"></i> Have a coupon? <a href="#have-coupon" class="text-white text-decoration-underline" data-toggle="collapse">Click here to enter your code</a></h3>
                    <div id="have-coupon" class="collapse coupon-checkout-content">
                        <div class="discount-coupon cart-note">
                            @php $codex = "";
                            if (Session::has('coupon')) {
                                $coupon = Session::get('coupon');
                                $codex = $coupon['code']; 

                                if($coupon['type'] == 0) {
                                    $spval = "S";
                                    $fvalx = $coupon['amt'];
                                    $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                                } else {
                                    $spval = "%";
                                    $fvalx = (beau_price(Cart::priceTotal()) * $coupon['amt']) / 100;
                                    $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                                }
                            }
                            @endphp
                            <div class="form-group">
                                <input type="text" name="coupon" id="couponx" class="upparcas" value="{{ $codex }}" placeholder="Enter your coupon code">
                            </div>
                            <div class="actionRow">
                                <div class="text-left"><input type="button" class="btn btn-secondary btn--small addcoupon" value="Apply Coupon"></div>
                                <div class="text-right"><button type="button" class="btn btn-secondary btn--small removecoupon"><i class="icon icon anm anm-times-l"></i> {{ $codex }}</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ route('checkout2') }}" id="CustomercheckoutForm" accept-charset="UTF-8" class="checkout-form" enctype="multipart/form-data">	
        @csrf
        <div class="row billing-fields">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 sm-margin-30px-bottom">
                <div class="create-ac-content bg-light-gray padding-20px-all">                    
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-8 spx_title">
                                    <h2 class="">shipping address:</h2>
                                </div>
                                <div class="form-group col-md-4 col-lg-4 col-xl-4  text-right">
                                    @if($bill_add->isEmpty())
                                        @if(Auth::check())
                                            <a class="btn btn--secondary add_link" href="{{ route('address') }}">Add new address</a>
                                        @endif
                                    @else
                                        <select name="bill_add_list" id="input_bill_add_list">
                                            <option value="0">- Select Address -</option>
                                            @foreach ($bill_add as $billadd)
                                                <option value="{{ $billadd->id }}">{{ $billadd->uniquename }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    @if(!Auth::check())
                                        <input name="ship_email" id="input-email" type="email" placeholder="Email *" required>
                                    @else
                                        <input name="ship_email" id="input-email" type="email" placeholder="Email *" required value="{{ Auth::user()->email }}" disabled>
                                    @endif
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    @if(!Auth::check())
                                        <input name="ship_contact" id="input-telephone" type="tel" placeholder="Contact *" required value="">
                                    @else
                                        <input name="ship_contact" id="input-telephone" type="tel" placeholder="Contact *" required value="{{ Auth::user()->contact }}">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="ship_firstname" id="input-firstname" class="ship_address_data" type="text" placeholder="First Name *" required>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="ship_lastname" id="input-lastname" class="ship_address_data" type="text" placeholder="Last Name *" required>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="ship_address_1" id="input-address-1" class="ship_address_data" type="text" placeholder="Address *" required>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <input name="ship_address_2" id="input-address-2" class="ship_address_data" type="text" placeholder="Apartment, suite, etc.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="ship_city" id="input-city" class="ship_address_data" type="text" placeholder="City *" required>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="ship_postcode" id="input-postcode" class="ship_address_data" type="text" placeholder="Post code / Zip code *" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <select name="ship_country" id="input_country_ship" class="ship_address_databox" required>
                                        <option value=""> --- Select Country--- </option>
                                        @foreach ($country as $coun)
                                            <option value="{{ $coun->id }}">{{ $coun->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <select name="ship_zone" id="input_state_ship" class="ship_address_databox" required>
                                        <option value=""> --- Select Region / State --- </option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-xl-12">
                                    <textarea class="form-control resize-both" rows="2" name="customernote" placeholder="Order Notes"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                @if(!Auth::check())
                                <div class="form-group form-check col-md-6 col-lg-6 col-xl-5 required">
                                    <label class="form-check-label padding-15px-left">
                                        <input type="checkbox" class="form-check-input" value="crete_acc" name="crete_acc"><strong>Create an account?</strong>
                                    </label>
                                </div>
                                @endif
                                <div class="form-group form-check col-md-6 col-lg-6 col-xl-6 required">
                                    <label class="form-check-label padding-15px-left">
                                        <input type="checkbox" class="form-check-input diff_bill" name="diff_billx"><strong>Use a different billing address?</strong>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="billaddress">
                            <div class="row">
                                <div class="form-group col-md-8 col-lg-8 col-xl-8 spx_title">
                                    <h2 class="">Billing Address: </h2>
                                </div>
                                <div class="form-group col-md-4 col-lg-4 col-xl-4 text-right">
                                    @if($ship_add->isEmpty())
                                        @if(Auth::check())
                                            <a class="btn btn--secondary add_link" href="{{ route('address') }}">Add new address</a>
                                        @endif
                                    @else
                                        <select name="ship_add_list" id="input_ship_add_list">
                                            <option value="0">- Select Address -</option>
                                            @foreach ($ship_add as $shipadd)
                                                <option value="{{ $shipadd->id }}">{{ $shipadd->uniquename }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="bill_firstname" id="bill_firstname" class="bill_address_data" type="text" placeholder="First name">
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <input name="bill_lastname" id="bill_lastname" class="bill_address_data" type="text" placeholder="Last name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="bill_address_1" id="bill_address_1" class="bill_address_data" type="text" placeholder="Address">
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <input name="bill_address_2" id="bill_address_2" class="bill_address_data" type="text" placeholder="Apartment, suite, etc.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="bill_city" id="bill_city" type="text" class="bill_address_data" placeholder="City *">
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <input name="bill_postcode" id="bill_postcode" type="text" class="bill_address_data" placeholder="Post Code *">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <select name="bill_country" id="input_country_bill" class="bill_country bill_address_databox">
                                        <option value=""> --- Select Country--- </option>
                                        @foreach ($country as $coun)
                                            <option value="{{ $coun->id }}">{{ $coun->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                    <select name="bill_zone" id="input_state_bill" class="bill_zone bill_address_databox">
                                        <option value=""> --- Select Region / State --- </option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="your-order-payment">
                    <div class="your-order">
                        <h2 class="order-title mb-4">Your Order</h2>
                        <div class="table-responsive-sm order-table"> 
                            <table class="bg-white table table-bordered table-hover text-center">
                                <thead>
                                    <tr>
                                        <th class="text-left">Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    foreach(Cart::content() as $row) {
                                        if($row->options['type'] == 1) { 
                                            $typex = " "; $variation_data = ""; 
                                        } else { 
                                            $variation_data = '<br>'.getvariationdata($row->options['idx'], 1);
                                        }
                                        echo '
                                        <tr>
                                            <td class="text-left">'.$row->name.' '.$variation_data.'</td>
                                            <td>'.$row->qty.'</td>
                                            <td>'.$currency.$row->price.'</td>
                                            <td>'.$currency.$row->price*$row->qty.'.00</td>
                                        </tr>
                                        ';
                                    }
                                    @endphp
                                </tbody>
                                <tfoot class="font-weight-600">
                                    @php
                                        if (Session::has('coupon')) {
                                            $coupon = Session::get('coupon');
                                            if($coupon['type'] == 0) {
                                                $spval = "S";
                                                $fvalx = $coupon['amt'].'.00';
                                                $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                                            } else {
                                                $spval = "%";
                                                $fvalx = (beau_price(Cart::priceTotal()) * $coupon['amt']) / 100;
                                                $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                                            }
                                        } else {
                                            $coupon['code'] = ""; $spval = ""; $fvalx = ""; $finaltot = beau_price(Cart::priceTotal());
                                        }
                                    @endphp
                                    <tr class="syscoupon">
                                        <td colspan="3" class="text-right couponxd">Discount: {{ $coupon['code'] }} ({{ $spval }})</td>
                                        <td class="couponxv">{{ $currency }}{{ $fvalx }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="3" class="text-right">Shipping </td>
                                        <td>$50.00</td>
                                    </tr> --}}
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td class="ffprice">{{ $currency }}{{ $finaltot }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <div class="your-payment">
                        <h2 class="payment-title mb-3">payment</h2>
                        <div class="payment-method">
                            <input type="hidden" id="bill_addr" value="0" name="bill_addr" />
                            <input type="hidden" class="paytype" value="" />
                            <p>{{ $cod_sts->val1 }}</p>
                            <div class="payment-accordion">
                                <div id="accordion" class="payment-section">
                                    @if($paypal_sts->val5 == 1)
                                    <div class="card mb-2">
                                        <div class="card-header card-link">
                                            <input type="radio" name="payment_method" value="pay-paypal" id="pay-paypal" class="radio-input" required>
                                            PayPal
                                        </div>
                                        <div class="pay-paypal description hideshow">
                                            <div class="card-body">
                                                <p class="no-margin font-15">PayPal offers a secure and easy way to send and receive payments online. Accept credit cards, debit cards, and PayPal balances globally with instant processing and buyer protection.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif @if($stripe_sts->val5 == 1)

                                    <div class="card mb-2">
                                        <div class="card-header card-link">
                                            <input type="radio" name="payment_method" value="pay-stripe" id="pay-stripe" class="radio-input" required>
                                            Stripe
                                        </div>
                                        <div class="pay-stripe description hideshow">
                                            <div class="card-body">
                                                <p class="no-margin font-15">Securely process payments with Stripe, accepting credit cards, debit cards, and more. Fast, reliable transactions with global reach and strong encryption for your protection.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif @if($razorpay_sts->val5 == 1)
                                    <div class="card mb-2">
                                        <div class="card-header card-link">
                                            <input type="radio" name="payment_method" value="pay-razorpay" id="pay-razorpay" class="radio-input" required>
                                            Razorpay
                                        </div>
                                        <div class="pay-razorpay description hideshow">
                                            <div class="card-body">
                                                <p class="no-margin font-15">Razorpay is a secure and fast payment gateway that enables businesses to accept online payments via credit cards, debit cards, UPI, and wallets.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif @if($cod_sts->val5 == 1)
                                    <div class="card mb-2">
                                        <div class="card-header card-link">
                                            <input type="radio" name="payment_method" value="pay-cod" id="pay-cod" class="radio-input" required>
                                            Cash On Delivery (COD)
                                        </div>
                                        <div class="pay-cod description hideshow">
                                            <div class="card-body">
                                                <p class="no-margin font-15">Pay in cash upon receiving their order at delivery.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="order-button-payment">
                                <p class="cart_tearm">
                                    <label>
                                        <input type="checkbox" name="tearm" class="checkbox tearm" value="tearm" required="required">
                                        I have read and agree to the website Terms and conditions
                                    </label>
                                    </p>
                                <button class="btn" value="Place order" type="submit" id="placeorder">Place order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>  
</div>

<style>
    .payment-accordion .card .card-link {
        display: block;
        font-size: 16px;
        font-weight: 500;
        text-transform: none;
    }
    .description {
        display: none;
    
    }
    .form-control {
        font-size: 14px;
    } 
    .add_link {
        padding: 2px 9px;
        font-size: 13px;
    }
    #CustomercheckoutForm strong {
        margin-top: 2px;
        display: block;
    }
    .spx_title {
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
</style>   
    
@endsection

@push('scripts')

    <script>
        $(document).ready(function(){ 
            //addcoupon
            $('.removecoupon').hide(); $('.syscoupon').hide();
            if($('#couponx').val() != "") { $('.removecoupon').show(); $('.coupon-checkout-content').addClass('show'); $('.syscoupon').show(); } 
            $('.addcoupon').on('click', function(){ $('.removecoupon').hide();
                var codex = $('#couponx').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('chkcoupon') }}",
                    data: { _token: "{{ csrf_token() }}", codex: codex },
                    success: function(data){
                        if(data.status == "success"){ 
                            $('.removecoupon').html('<i class="icon icon anm anm-times-l"></i> ' + codex);
                            flasher.success(data.message);
                            //
                            $('.coupon-checkout-content').addClass('show');
                            $('.couponxd').html('Discount: ' + data.htmlx);
                            $('.couponxv').text("{!! $currency !!}" +  data.discount + '.00');
                            $('.ffprice').text("{!! $currency !!}" + data.ftot + '.00');
                            $('.removecoupon').show();
                            $('.syscoupon').show();
                        } else {
                            flasher.warning(data.message);
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //remove coupon
            $('.removecoupon').on('click', function(){ $('.removecoupon').hide();
                $.ajax({
                    method: "POST",
                    url: "{{ route('rmovecoupon') }}",
                    data: { _token: "{{ csrf_token() }}", codex: 1 },
                    success: function(data){
                        if(data.status == "success"){ 
                            $('.removecoupon').html('');
                            flasher.success(data.message);
                            //
                            $('#couponx').val('');
                            $('.couponxd').html('Discount: ');
                            $('.couponxv').text("{!! $currency !!}" + '0.00');
                            $('.ffprice').text("{!! $currency !!}" + data.ftot);
                            $('.syscoupon').hide();
                            $('.removecoupon').hide();
                            $('.coupon-checkout-content').removeClass('show');
                        } else {
                            flasher.warning(data.message);
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('.billaddress').hide();
            $(".diff_bill").click(function() {
                var checked = $(this).is(':checked');
                if (checked) {
                    $('#bill_addr').val('1');
                    $('.billaddress').show();
                    $('#bill_firstname, #bill_lastname, #bill_address_1, #bill_city, #bill_postcode, .bill_country, .bill_zone').attr('required', 'required');
                } else {
                    $('#bill_addr').val('0');
                    $('.billaddress').hide();
                    $('#bill_firstname, #bill_lastname, #bill_address_1, #bill_city, #bill_postcode, .bill_country, .bill_zone').removeAttr('required', 'required');
                }
            });
            //
            $('#input_country_ship').on('change', function(){
                var idx = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('getstate') }}",
                    data: { idx: idx, _token: "{{ csrf_token() }}" },
                    success: function(data){ $('#input_state_ship').empty();
                        $('#input_state_ship').append(data);
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('#input_country_bill').on('change', function(){
                var idx = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('getstate') }}",
                    data: { idx: idx, _token: "{{ csrf_token() }}" },
                    success: function(data){ $('#input_state_bill').empty();
                        $('#input_state_bill').append(data);
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('#input_bill_add_list').on('change', function(){
                var idx = $(this).val();
                if(idx != '0') {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('getaddress') }}",
                        data: { idx: idx, _token: "{{ csrf_token() }}" },
                        success: function(data){ 
                            if(data) {
                                $('.ship_address_data').val('');
                                $('.ship_address_databox').val(null);
                                $('#input-firstname').val(data.addressx.firstname);
                                $('#input-lastname').val(data.addressx.lastname);
                                $('#input-address-1').val(data.addressx.address_1);
                                $('#input-address-2').val(data.addressx.address_2);
                                $('#input-city').val(data.addressx.city);
                                $('#input-postcode').val(data.addressx.zipcode);
                                $('#input_country_ship').val(data.addressx.country_id);                            
                                $('#input_state_ship').append("<option id='"+data.statex.id+"' selected='selected'>"+data.statex.name+"</option>");                            
                            }
                        },
                        error: function(data){ console.log(data); }
                    });
                } else {
                    $('.ship_address_data').val('');
                    $('.ship_address_databox').val(null);
                    $('#input_state_ship').empty();  
                    $('#input_state_ship').append("<option id='0' selected='selected'> --- Select Region / State --- </option>");                            
                }
            });
            //
            $('#input_ship_add_list').on('change', function(){
                var idx = $(this).val();
                if(idx != '0') {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('getaddress') }}",
                        data: { idx: idx, _token: "{{ csrf_token() }}" },
                        success: function(data){ 
                            if(data) {
                                $('.bill_address_data').val('');
                                $('.bill_address_databox').val(null);
                                $('#bill_firstname').val(data.addressx.firstname);
                                $('#bill_lastname').val(data.addressx.lastname);
                                $('#bill_address_1').val(data.addressx.address_1);
                                $('#bill_address_2').val(data.addressx.address_2);
                                $('#bill_city').val(data.addressx.city);
                                $('#bill_postcode').val(data.addressx.zipcode);
                                $('#input_country_bill').val(data.addressx.country_id);                            
                                $('#input_state_bill').append("<option id='"+data.statex.id+"' selected='selected'>"+data.statex.name+"</option>");                            
                            }
                        },
                        error: function(data){ console.log(data); }
                    });
                } else {
                    $('.bill_address_data').val('');
                    $('.bill_address_databox').val(null);
                    $('#input_state_bill').empty();  
                    $('#input_state_bill').append("<option id='0' selected='selected'> --- Select Region / State --- </option>");                            
                }
            });
            //
            $(".card-link").click(function() {
                $('.hideshow').addClass('description');
                $('input[type="radio"]').prop('checked', false);
                var inputId = $(this).find('input[type="radio"]').attr('id');
                var radioButton = $(this).find('input[type="radio"]');
                radioButton.prop('checked', true);
                var inputId = radioButton.attr('id');
                $('.' + inputId).removeClass('description');
                if(inputId == "pay-razorpay") {
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://checkout.razorpay.com/v1/checkout.js';
                    $('.order-button-payment').append(script);
                }
            });
            //
            $("#placeorder__").click(function() {
                if (!$('.tearm').is(':checked')) {
                    flasher.info("Please checked 'Terms and conditions' checkbox");
                } else {
                    if (!$('.radio-input').is(':checked')) {
                        flasher.info("Please select any payment method");
                    } else {
                        var checkedRadioId = $('.radio-input:checked').attr('id');
                        $('#paytype').val(checkedRadioId);
                        var formData = $('#CustomercheckoutForm').serialize();  
                        $.ajax({
                            url: '{{ route("checkout2") }}', 
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                //alert('Form submitted successfully!');
                                if(response == 101) {
                                    // Make an AJAX call to create the order on your server
                                    fetch('{{ route("razorpay.charge") }}', {
                                        method: 'POST', 
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        },
                                        body: JSON.stringify({ // Additional data can be sent here if necessary
                                        }),
                                    })
                                    .then(response => response.json())
                                    .then(function(data) {
                                        var options = {
                                            key: data.key, // Razorpay Key
                                            amount: 50000, // Amount in paise
                                            currency: 'INR',
                                            name: 'Estore - Online Store',
                                            description: 'Test Payment',
                                            image: 'http://estore.test/fassets/images/logo.svg',
                                            order_id: data.order_id, // Order ID created on the server
                                            handler: function(response){
                                                alert('Payment Successful');
                                                console.log(response);
                                            },
                                            // prefill: {
                                            //     name: 'Customer Name',
                                            //     email: 'customer@example.com',
                                            //     contact: '9999999999'
                                            // },
                                            // notes: {
                                            //     address: 'Razorpay Corporate Office'
                                            // },
                                            // theme: {
                                            //     color: '#F37254'
                                            // }
                                        };
                                        var rzp1 = new Razorpay(options);
                                        rzp1.open();
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Error submitting form: ' + error);
                            }
                        });
                    }
                    
                }
            });

        });
    </script>

@endpush    