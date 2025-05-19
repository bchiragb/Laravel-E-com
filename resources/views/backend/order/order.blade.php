@extends('backend.layout.master')

@section('admin_body_content')

@php
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
@endphp

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Order #{{ $id }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.order') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        {{-- http://estore.test/admin/order/7094245827 --}}
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <address>
                                        <strong>Order Details:</strong>
                                        <span class="labelX">Order Date: {{ date('F j, Y - H:i:s', strtotime($order->order_date)) }}</span>
                                        <span class="labelX">Order Status: {{ order_sts($order->order_status) }}</span>
                                        <span class="labelX">Order Note: {{ $array_data['form']['customernote']; }}</span>
                                    </address>
                                </div>
                                <div class="col-md-4">
                                    <address>
                                    <strong>Payment / Transaction Details:</strong>
                                        <span class="labelX">Payment Mode: {{ $order->payment_mode }}</span>
                                        <span class="labelX">Transaction ID: {{ $order->transaction_id }}</span>
                                        <span class="labelX">Transaction Status: {{ $order->transacion_status }}</span>
                                    </address>
                                </div>
                                <div class="col-md-4 text-md-rightx">
                                    <address>
                                    <strong>User Details:</strong>
                                        <span class="labelX">Name: {{ $user->name }}</span>
                                        <span class="labelX">Email: {{ $user->email }}</span>
                                        <span class="labelX">Contact: {{ $user->contact }}</span>
                                    </address>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <address>
                                        <strong>Shipping Address:</strong>
                                        <span class="labelX">{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</span>
                                        <span class="labelX">{{ $array_data['form']['ship_address_1']; }}</span>
                                        <span class="labelX">{{ $array_data['form']['ship_address_2']; }}</span>
                                        <span class="labelX">{{ getcountrynm($array_data['form']['ship_country']) }}</span>
                                        <span class="labelX">{{ getstatenm($array_data['form']['ship_zone']) }}</span>
                                        <span class="labelX">{{ $array_data['form']['ship_city']; }} - {{ $array_data['form']['ship_postcode']; }}</span>
                                    </address>
                                </div>
                                <div class="col-md-4">
                                    <address>
                                        <strong>Billing Address:</strong>
                                        @if($array_data['form']['bill_address_1'] == "") 
                                            <span class="labelX">{{ $array_data['form']['ship_firstname']; }} {{ $array_data['form']['ship_lastname']; }}</span>
                                            <span class="labelX">{{ $array_data['form']['ship_address_1']; }}</span>
                                            <span class="labelX">{{ $array_data['form']['ship_address_2']; }}</span>
                                            <span class="labelX">{{ getcountrynm($array_data['form']['ship_country']) }}</span>
                                            <span class="labelX">{{ getstatenm($array_data['form']['ship_zone']) }}</span>
                                            <span class="labelX">{{ $array_data['form']['ship_city']; }} - {{ $array_data['form']['ship_postcode']; }}</span>
                                        @else
                                            <span class="labelX">{{ $array_data['form']['bill_firstname']; }} {{ $array_data['form']['bill_lastname']; }}</span>
                                            <span class="labelX">{{ $array_data['form']['bill_address_1']; }}</span>
                                            <span class="labelX">{{ $array_data['form']['bill_address_2']; }}</span>
                                            <span class="labelX">{{ getcountrynm($array_data['form']['bill_country']) }}</span>
                                            <span class="labelX">{{ getstatenm($array_data['form']['bill_zone']) }}</span>
                                            <span class="labelX">{{ $array_data['form']['bill_city']; }} - {{ $array_data['form']['bill_postcode']; }}</span>
                                        @endif
                                    </address>
                                </div>
                                <div class="col-md-4 text-md-rightx">
                                    <address>
                                        <strong>Courier Details:</strong>
                                        @if($ship)
                                            @php $datex = date('F j, Y', strtotime($ship->sdate)); @endphp
                                            <span class="labelX">Courier Date: {{ $datex }}</span>
                                            <span class="labelX">Courier Provider: {{ $ship->sname }}</span>
                                            <span class="labelX">Courier Provider URL: <a href="{{ $ship->surl }}">View</a></span>
                                            <span class="labelX">Tracking Code: {{ $ship->sno }}</span>
                                        @else
                                            <div class="row pb-2 pt-2">
                                                <span class="col-12 col-sm-6 cart__subtotal-title">No details</span>
                                            </div>
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <hr>
                            <div class="section-title">Order Summary</div>
                            <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tr>
                                    <th data-width="40">#</th>
                                    <th colspan="2">Item</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Totals</th>
                                </tr>
                                @php $pr = 1;
                                    foreach ($array_data['product'] as $key => $value) {
                                        $prodata = '
                                            <tr class="cart__row border-bottom line1 cart-flex border-top">
                                                <td class="cart__price-wrapper cart-flex-item">
                                                    <span class="money">'.$pr.'</span>
                                                </td>
                                                <td class="cart__image-wrapper cart-flex-item">
                                                    <a href="'.route('product', [$value['options']['slug']]).'"><img class="cart__image" src="'.asset($value['options']['img']).'" alt="" style="width: 50px;"></a>
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
                                                <td class="cart__price-wrapper cart-flex-item text-md-center">
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
                                        echo $prodata; $pr++;
                                    }
                                @endphp
                            </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-3 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-value">Subtotal: <b>{{ $currency }}{{ $order->total_amount }}.00</b></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-value">Discount / Coupon: <b>{{ $currency }}{{ $order->discount_amt }}.00</b></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-value">Shipping: <b>Free shipping .00</b></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-value">Total: <b>{{ $currency }}{{ $order->total_amount-$order->discount_amt }}.00</b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <hr>
                            <div class="row mt-4">
                                <div class="col-lg-3">
                                    <form action="{{ route('admin.order.sts') }}" method="post" id="ord_mgmt" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $id }}" name="orderno" />
                                        <div class="section-title">Change Order Status:</div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Related mail send after changed status:</label>
                                                <select name="order_sts" class="order_sts form-control">
                                                    <option @php if($order->order_status ==1){ echo "selected"; } @endphp value="1">Complete</option>
                                                    <option @php if($order->order_status ==2){ echo "selected"; } @endphp value="2">Shipped</option>
                                                    <option @php if($order->order_status ==3){ echo "selected"; } @endphp value="3">Delivered</option>
                                                    <option @php if($order->order_status ==4){ echo "selected"; } @endphp value="4">Cancelled</option>
                                                    <option @php if($order->order_status ==5){ echo "selected"; } @endphp value="5">Refund</option>
                                                    <option @php if($order->order_status ==6){ echo "selected"; } @endphp value="6">Pending Payment</option>
                                                    <option @php if($order->order_status ==7){ echo "selected"; } @endphp value="7">Failed</option>
                                                    <option @php if($order->order_status ==8){ echo "selected"; } @endphp value="8">Item Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row cancelord diseble">
                                            <div class="form-group col-12">
                                                <label>Order Cancellation Reason:</label>
                                                <input type="text" name="cancel_reson" class="form-control" value="{{ $can_ref_tbl->cancelnote ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row cancelord diseble">
                                            <div class="form-group col-12">
                                                <label>Order Cancellation By:</label>
                                                @php if($order->order_status == 4) {
                                                    $typ = $can_ref_tbl->cancelby;
                                                    if($typ == 2) { $byx = "Admin"; } else { $byx = "User"; } }
                                                @endphp
                                                <input type="text" class="form-control" value="{{ $byx ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-3">
                                    <form action="{{ route('admin.order.shipping') }}" method="post" id="ord_mgmt2" enctype="multipart/form-data">
                                        <div class="section-title">Add Shipping Details: </div>
                                        @csrf
                                        @php if(empty($ship)) { $ship = new stdClass(); $ship->sname = ''; $ship->surl = ''; $ship->sno = ''; } @endphp
                                        <input type="hidden" value="{{ $id }}" name="orderno" />
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Shipping Provider:</label>
                                                <input type="text" name="ship_pro" class="form-control" value="{{ $ship->sname }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Shipping URL:</label>
                                                <input type="text" name="ship_url" class="form-control" value="{{ $ship->surl }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Tracking Code:</label>
                                                <input type="text" name="ship_code" class="form-control" value="{{ $ship->sno }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                                <div class="col-lg-3">
                                    <form action="{{ route('admin.order.note') }}" method="post" id="ord_mgmt" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $id }}" name="orderno" />
                                        <div class="section-title">Admin Note:</div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Only visible for admin:</label>
                                                <textarea name="admin_note" class="form-control txta_box">{{ $order->admin_note }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>                               
                                <div class="col-lg-3">
                                    <div class="section-title">Mail Re-Sender: </div>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>Automatic send mail after change option</label>
                                                <select name="brand" class="form-control">
                                                    <option value="0">- Select -</option>
                                                    <option value="1">Complete</option>
                                                    <option value="2">Shipped</option>
                                                    <option value="3">Delivered</option>
                                                    <option value="4">Cancelled</option>
                                                    <option value="5">Refund</option>
                                                    <option value="6">Pending Payment</option>
                                                    <option value="7">Failed</option>
                                                    <option value="8">Item Returned</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .section .section-title:before, .diseble {
        display: none;
    }
    .invoice .invoice-detail-item .invoice-detail-value {
        font-weight: 500;
    }
    address strong {
        color: #333;
        font-size: 16px;
        margin-bottom: 10px;
        display: flex;
    }
    span.labelX {
        display: block;
        margin-bottom: 2px;
        font-size: 14px;
        color: #444;
    }
    .invoice .invoice-detail-item .invoice-detail-value {
        font-size: 16px;
    }
    .section .section-title {
        margin: 0px 0 10px 0;
    }
    textarea.form-control {
        height: 128px !important;
    }
    .invoice hr {
        margin-top: 10px;
        margin-bottom: 20px;
        border-top-color: #ccc;
    }
</style>

@endsection

@push('scripts')

    <script>
        $(document).ready(function(){
            $('.order_sts').on('change', function(){
                if($(this).val() == 4) {
                    $('.cancelord').removeClass('diseble');
                } else {
                    $('.cancelord').addClass('diseble');
                }
            });
            var valx = $('.order_sts').val();
            if(valx == 4){ $('.cancelord').removeClass('diseble'); }
        });
    </script>
    
@endpush

