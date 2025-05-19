@extends('frontend.layout.master')

@section('body_content')

@php
    //$order = DB::table('order')->where('order_id', $id)->first();
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
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Order History</h1></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col">
                	<div class="cart style2">
                		<div class="containerx mt-4">
                            <div class="row cart__footer">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                                    <div class="solid-border boxr">
                                        <h5>Payment Details</h5>
                                        <hr>
                                        @php if($order->order_status == 4 && $can_ref_tbl->cancelby == 2){
                                            $byx = "Admin";
                                        } else {
                                            $byx = "User";
                                        }
                                        @endphp
                                        <div class="form-groupxz"><label><h5>Order No: {{ $order->order_id }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>Order Date: {{ date('F j, Y', strtotime($order->order_date)) }}</h5></div>
                                        <div class="form-groupxz"><label><h5>Payment: {{ $paytye }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>Order Status: <b>{{ order_sts($order->order_status) }}</b></h5></div>
                                        <div class="form-groupxz"><label><h5></h5></label></div>
                                        @if($order->order_status == 4)
                                        <div class="form-groupxz"><label><h5>Order Cancelled By: {{ $byx }}</h5></div>
                                        <div class="form-groupxz"><label><h5>Cancelled Note: {{ $can_ref_tbl->cancelnote }}</h5></div>
                                        @endif
                                        <div class="form-groupxz"><label><h5></h5></label></div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                                    <div class="solid-border boxr">
                                        <h5>Order Details</h5>
                                        <hr>
                                        <div class="form-groupxz"><label><h5>Subtotal: {{ $currency }}{{ $order->total_amount }}.00</h5></label></div>
                                        <div class="form-groupxz"><label><h5>Coupon: {{ $currency }}{{ $order->discount_amt }}.00</h5></div>
                                        <div class="form-groupxz"><label><h5>Shipping: Free shipping</h5></div>
                                        <div class="form-groupxz"><label><h5>Grand Total: {{ $currency }}{{ $order->total_amount-$order->discount_amt }}.00</h5></label></div>
                                        <div class="form-groupxz"><label><h5>Note: {{ $array_data['form']['customernote']; }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5></h5></label></div>
                                        <div class="form-groupxz"><label><h5></h5></label></div>
                                        <div class="form-groupxz"><label><h5></h5></label></div>
                                    </div>
                                </div>                                                        
                            </div>
                        </div>
                    </div>                   
               	</div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col">
                	<div class="cart style2">
                		<div class="containerx mt-4">
                            <div class="row cart__footer">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                                    <div class="solid-border boxr">
                                        <h5>Shipping Address</h5>
                                        <hr>
                                        <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_address_1']; }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_address_2']; }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ getcountrynm($array_data['form']['ship_country']) }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ getstatenm($array_data['form']['ship_zone']) }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_city']; }}</h5></label></div>
                                        <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_postcode']; }}</h5></label></div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                                    <div class="solid-border boxr">
                                        <h5>Billing Address</h5>
                                        <hr>
                                        @if($array_data['form']['bill_address_1'] == "") 
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_address_1']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_address_2']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ getcountrynm($array_data['form']['ship_country']) }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ getstatenm($array_data['form']['ship_zone']) }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_city']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['ship_postcode']; }}</h5></label></div>
                                        @else 
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['bill_firstname']; }} {{ $array_data['form']['bill_lastname']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['bill_address_1']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['bill_address_2']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ getcountrynm($array_data['form']['bill_country']) }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ getstatenm($array_data['form']['bill_zone']) }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['bill_city']; }}</h5></label></div>
                                            <div class="form-groupxz"><label><h5>{{ $array_data['form']['bill_postcode']; }}</h5></label></div>
                                        @endif
                                    </div>
                                </div>                                                        
                            </div>
                        </div>
                    </div>                   
               	</div>
            </div>
        	<div class="row">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 main-col">
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
                    </div>                   
               	</div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
                    @if($order->order_status == 1)
                    <div class="cart-note">
                        <div class="solid-border fexi">
                            <h5>Order Cancellation:</h5>
                            <form action="{{ route('cancel.order', ['id' => $order->order_id]) }}" method="post" id="ord_mgmt" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $order->order_id }}" name="orderno" />
                                <div class="form-group">
                                    <label>Are you want to cancel this order?</label>
                                    <input type="text" name="cancel_reason" placeholder="Reason for cancel order" required>
                                </div>
                                <div class="actionRow">
                                    <div class="text-left"><input type="submit" class="btn btn-secondary btn--small addcoupon" value="Cancel Order"></div>
                                </div>
                            </form>

                        </div>
                    </div>
                    @endif
                    <div class="solid-border sp_zbox fexi">	
                        <h5>Courier Services Details</h5>
                        <hr>
                        @if($ship)
                        @php
                            $datex = date('F j, Y', strtotime($ship->sdate));
                        @endphp 
                        <div class="row border-bottom pb-2">
                            <span class="col-12 col-sm-6 cart__subtotal-title">Courier Date</span>
                            <span class="col-12 col-sm-6 text-right"><span class="money">{{ $datex }}</span></span>
                        </div>
                        <div class="row border-bottom pb-2 pt-2">
                            <span class="col-12 col-sm-6 cart__subtotal-title">Courier Provider</span>
                            <span class="col-12 col-sm-6 text-right">{{ $ship->sname }}</span>
                        </div>
                        <div class="row border-bottom pb-2 pt-2">
                            <span class="col-12 col-sm-6 cart__subtotal-title">Courier Provider Link</span>
                            <span class="col-12 col-sm-6 text-right"><a href="{{ $ship->surl }}">View</a></span>
                        </div>
                        <div class="row pb-2 pt-2">
                            <span class="col-12 col-sm-6 cart__subtotal-title">Tracking Code</span>
                            <span class="col-12 col-sm-6 text-right">{{ $ship->sno }}</span>
                        </div>
                        @else
                        <div class="row pb-2 pt-2">
                            <span class="col-12 col-sm-6 cart__subtotal-title">No details</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .sp_zbox .cart__subtotal-title {
        text-transform: none !important;
    }
    .cart__footer .solid-border {
        min-height: 350px;
    }
    .cart__footer .solid-border.fexi {
        min-height: 150px;
    }
    .form-groupxz h5 {
        text-transform: initial !important;
    }
    b, strong {
        font-weight: 600 !important;
    }
</style>
@endsection

@push('scripts')


@endpush