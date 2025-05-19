@extends('frontend.layout.master')

@section('body_content')

@php
    $order = DB::table('order')->where('order_id', $id)->first();
    //
    $paytye = '';
    if($order->payment_mode == "pay-cod" ) {
        $paytye = 'Cash On Delivery';
    } else if($order->payment_mode == "pay-paypal" ) {
        $paytye = 'Paypal';
    } else if($order->payment_mode == "pay-stripe" ) {
        $paytye = 'Stripe';
    } else if($order->payment_mode == "pay-razorpay" ) {
        $paytye = 'Razorpay';
    }
    //
    $raw_data = $order->rawdata;
    $array_data = json_decode($raw_data, true);
    //\Carbon\Carbon::parse($order->order_date)->format('F j, Y')
    //
    if(Auth::check()) { $usr_email = Auth::user()->email; }

    //echo "<pre>"; print_r($array_data);
    if(!array_key_exists('ship_email', $array_data['form'])) { 
        //$array_data['ship']['user_id'];
    }    
@endphp

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Order Received</h1></div>
          </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                <div class="customer-box returning-customer">
                    <h3><i class="icon fa fa-check-circle"></i> Thank you for your order</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3 cart__footer">
                <div class="solid-border">	
                    <h4>Order Details</h4>
                    <hr>
                    <div class="row">
                    	<div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order No: <b>{{ $order->order_id }}</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order Date: <b>{{ date('F j, Y', strtotime($order->order_date)) }}</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order Status: <b>{{ $order->total_amount }}</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Payment Mode: <b>{{ $paytye }}</b></h5>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Email: <b>{{ $usr_email }}</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Contact: <b>{{ $array_data['form']['ship_contact']; }}</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                            <h5>Note: <b>{{ $array_data['form']['customernote']; }}</b></h5>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Subtotal: <b>{{ $currency }}{{ $order->total_amount }}.00</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Coupon: <b>{{ $currency }}{{ $order->discount_amt }}.00</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Shipping: <b>Free shipping</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order Total: <b>{{ $currency }}{{ $order->total_amount-$order->discount_amt }}.00</b></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col">
                <div class="cart style2">
                    <table>
                        <thead class="cart__row cart__header">
                            <tr>
                                <th colspan="2" class="text-center">Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                foreach ($array_data['product'] as $key => $value) {
                                    $prodata = '
                                        <tr class="cart__row border-bottom line1 cart-flex border-top">
                                            <td class="cart__image-wrapper cart-flex-item">
                                                <a href="'.route('product', [$value['options']['slug']]).'"><img class="cart__image" src="'.asset($value['options']['img']).'" alt=""></a>
                                            </td>
                                            <td class="cart__meta small--text-left cart-flex-item">
                                                <div class="list-view-item__title">
                                                    <a href="'.route('product', [$value['options']['slug']]).'">'.$value['name'].'</a>
                                                </div>';
                                    if($value['options']['type'] == 2) {
                                        $prodata .= '
                                                <div class="cart__meta-text">'.getvariationdata($value['options']['idx'], 1).'</div>';
                                    }
                                    $prodata .= '
                                            </td>
                                            <td class="cart__price-wrapper cart-flex-item">
                                                <span class="money">'.$currency.$value['price'].'</span>
                                            </td>
                                            <td class="cart__update-wrapper cart-flex-item text-right">
                                                <div class="cart__qty text-center">
                                                    <div class="qtyField">'.$value['qty'].'</div>
                                                </div>
                                            </td>
                                            <td class="text-right small--hide cart-price">
                                                <div><span class="money">'.$currency.$value['subtotal'].'</span></div>
                                            </td>
                                        </tr>
                                    '; 
                                    echo $prodata;
                                }
                            @endphp
                        </tbody>
                    </table>
                    <div class="containerx mt-4">
                    </div>
                </div>                   
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 cart__footer">
                <div class="row cart__footer">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                        <div class="solid-border boxr">
                            <h5>Shipping Address</h5>
                            <hr>
                            <div class="form-groupx"><label>{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</label></div>
                            <div class="form-groupx"><label>{{ $array_data['form']['ship_address_1']; }}</label></div>
                            <div class="form-groupx"><label>{{ $array_data['form']['ship_address_2']; }}</label></div>
                            <div class="form-groupx"><label>{{ getcountrynm($array_data['form']['ship_country']) }}</label></div>
                            <div class="form-groupx"><label>{{ getstatenm($array_data['form']['ship_zone']) }}</label></div>
                            <div class="form-groupx"><label>{{ $array_data['form']['ship_city']; }}</label></div>
                            <div class="form-groupx"><label>{{ $array_data['form']['ship_postcode']; }}</label></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                        <div class="solid-border boxr">
                            <h5>Billing Address</h5>
                            <hr>
                            @if($array_data['form']['bill_address_1'] == "") 
                                <div class="form-groupx"><label>{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['ship_address_1']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['ship_address_2']; }}</label></div>
                                <div class="form-groupx"><label>{{ getcountrynm($array_data['form']['ship_country']) }}</label></div>
                                <div class="form-groupx"><label>{{ getstatenm($array_data['form']['ship_zone']) }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['ship_city']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['ship_postcode']; }}</label></div>
                            @else 
                                <div class="form-groupx"><label>{{ $array_data['form']['bill_firstname']; }} {{ $array_data['form']['bill_lastname']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['bill_address_1']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['bill_address_2']; }}</label></div>
                                <div class="form-groupx"><label>{{ getcountrynm($array_data['form']['bill_country']) }}</label></div>
                                <div class="form-groupx"><label>{{ getstatenm($array_data['form']['bill_zone']) }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['bill_city']; }}</label></div>
                                <div class="form-groupx"><label>{{ $array_data['form']['bill_postcode']; }}</label></div>
                            @endif
                        </div>
                    </div>                                                        
                </div>
            </div>
        </div>
    </div> 
</div>

<style>
.cart__footer h4 {
    color: #000;
    text-transform: uppercase;
    font-size: 16px;
    font-family: Poppins, Helvetica, Tahoma, Arial, sans-serif;
    letter-spacing: 0.02em;
}


</style>


@endsection

@push('scripts')

@endpush