@extends('frontend.layout.master')

@section('body_content')

@php
    $col_attr = \App\Models\SiteSetting::where('key', 'productsetting1')->first();
    $shipamt = $col_attr->val1;
    $remainval = calcfreeship();
@endphp

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Shopping Cart</h1></div>
          </div>
    </div>
    
    <div class="container">
        <div class="row">
            @if(Cart::content()->count() > 0) 
                <div class="col-12 col-sm-12 col-md-8 col-lg-8 main-col">
                    <div class="alert alert-success text-uppercase" role="alert">
                        {{-- <i class="icon anm anm-truck-l icon-large"></i> &nbsp; Get free shipping on orders above <strong>{{ $currency }}{{ $shipamt }}</strong>, ONLY {{ $remainval  }} AWAY FROM FREE SHIPPING! --}}
                        @if($remainval == 0)
                            <i class="icon anm anm-truck-l icon-large"></i> &nbsp; Free shipping Applied
                        @else
                            <i class="icon anm anm-truck-l icon-large"></i> &nbsp; Get free shipping? Only <strong>{{ $currency }}{{ $remainval }}</strong> AWAY FROM FREE SHIPPING!    
                        @endif
                        
                    </div>
                    <form action="#" method="post" class="cart style2">
                        <table>
                            <thead class="cart__row cart__header">
                                <tr>
                                    <th colspan="2" class="text-center">Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Total</th>
                                    <th class="action">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                foreach(Cart::content() as $row) { //echo "<pre>"; print_r($row);
                                    if($row->options['type'] == 1) { 
                                        $typex = " "; $variation_data = ""; 
                                    } else { 
                                        $variation_data = getvariationdata($row->options['idx'], 1);
                                    }
                                    echo '
                                    <tr class="cart__row border-bottom line1 cart-flex border-top product_'.$row->rowId.'" data="'.$row->rowId.'">
                                        <td class="cart__image-wrapper cart-flex-item">
                                            <a href="'.route('product', $row->options['slug']).'"><img class="cart__image" src="'.asset($row->options['img']).'" alt="'.$row->name.'"></a>
                                        </td>
                                        <td class="cart__meta small--text-left cart-flex-item">
                                            <div class="list-view-item__title"><a href="'.route('product', $row->options['slug']).'">'.$row->name.'</a></div>
                                            <div class="cart__meta-text">
                                                '.$variation_data.'
                                            </div>
                                        </td>
                                        <td class="cart__price-wrapper cart-flex-item">
                                            <span class="money">'.$currency.$row->price.'</span>
                                        </td>
                                        <td class="cart__update-wrapper cart-flex-item text-right">
                                            <div class="cart__qty text-center">
                                                <div class="qtyField">
                                                    <a class="qtyBtn minus qminusx" href="javascript:void(0);"><i class="icon icon-minus"></i></a>
                                                    <input class="cart__qty-input qty" type="text" name="updates[]" id="qty" value="'.$row->qty.'" pattern="[0-9]*">
                                                    <a class="qtyBtn plus qplusx" href="javascript:void(0);"><i class="icon icon-plus"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right small--hide cart-price">
                                            <div><span class="money">'.$currency.$row->price*$row->qty.'</span></div>
                                        </td>
                                        <td class="text-center small--hide"><a href="#" class="btn btn--secondary cart__remove rmove_itms" title="Remove item" data="'.$row->rowId.'"><i class="icon icon anm anm-times-l"></i></a></td>
                                    </tr>
                                    ';
                                }
                                @endphp
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-left"><a href="{{ route('home') }}" class="btn btn-secondary btn--small cart-continue">Continue shopping</a></td>
                                    <td colspan="3" class="text-right">
                                        <button type="button" name="clear" class="btn btn-secondary btn--small  small--hide" id="clearcart">Clear Cart</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>       
                    <hr>
                    <div id="shipping-calculator" class="mb-4">
                        <h5 class="small--text-center">Get shipping estimates</h5>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <select id="address_country" name="country_id" class="countryid">
                                        <option value="">Select Country</option>
                                        @foreach ($country as $coun)
                                            <option value="{{ $coun->id }}">{{ $coun->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    <select id="address_province" name="state_id" class="stateid">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3">
                                <div class="form-group">
                                    {{-- <label for="address_zip">Postal/Zip Code</label> --}}
                                    <input type="text" class="zip_id" name="zip_id" placeholder="Enter Postal/Zip Code">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 sptop">
                                <div class="form-group">
                                    <input type="button" class="btn btn-secondary btn--small get_rates" value="Calculate shipping">
                                </div>
                            </div>
                        </div>
                    </div> 
                    
                    </form>                   
                </div>
                @php $codex = "";
                if (Session::has('coupon')) {
                    $coupon = Session::get('coupon');
                    $codex = $coupon['code']; 
                }
                @endphp
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
                    <div class="cart-note">
                        <div class="solid-border">
                            <h5>Discount Codes</h5>
                            <div class="form-group">
                                <label for="address_zip">Enter your coupon code if you have</label>
                                <input type="text" name="coupon" id="couponx" class="upparcas" value="{{ $codex }}">
                            </div>
                            <div class="actionRow">
                                <div class="text-left"><input type="button" class="btn btn-secondary btn--small addcoupon" value="Apply Coupon"></div>
                                <div class="text-right"><button type="button" class="btn btn-secondary btn--small removecoupon"><i class="icon icon anm anm-times-l"></i> {{ $codex }}</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="solid-border">	
                        <div class="row border-bottom pb-2">
                        <span class="col-12 col-sm-6 cart__subtotal-title">Subtotal</span>
                        <span class="col-12 col-sm-6 text-right cartsubtot"><span class="money">{{ $currency }}{{ Cart::subtotal() }}</span></span>
                        </div>
                        <div class="row border-bottom pb-2 pt-2 shipbox">
                            <span class="col-12 col-sm-6 cart__subtotal-title">Shipping</span>
                            <span class="col-12 col-sm-6 text-right ship_val">-</span>
                        </div>
                        {{-- <div class="row border-bottom pb-2 pt-2">
                        <span class="col-12 col-sm-6 cart__subtotal-title">Tax</span>
                        <span class="col-12 col-sm-6 text-right">{{ $currency }}{{ Cart::tax() }}</span>
                        </div> --}}
                        @php  $finaltot = beau_price(Cart::priceTotal());
                        if (Session::has('coupon')) {
                            $coupon = Session::get('coupon');
                            if($coupon['type'] == 0) {
                                $spval = "S";
                                $fvalx = $coupon['amt'];
                                $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                            } else {
                                $spval = "%";
                                $fvalx = (beau_price(Cart::priceTotal()) * $coupon['amt']) / 100;
                                $finaltot = beau_price(Cart::priceTotal()) - $fvalx.'.00';
                            }

                            echo '
                            <div class="row border-bottom pb-2 pt-2 syscoupon">
                                <span class="col-12 col-sm-6 cart__subtotal-title couponxd">Discount: '.$coupon['code'].' ('.$spval.')</span>
                                <span class="col-12 col-sm-6 text-right couponxv">'.$currency.$fvalx.'.00</span>  
                            </div>
                            ';
                            
                        } else {
                            echo '
                            <div class="row border-bottom pb-2 pt-2 syscoupon">
                                <span class="col-12 col-sm-6 cart__subtotal-title couponxd">Discount: </span>
                                <span class="col-12 col-sm-6 text-right couponxv"></span>  
                            </div>
                            ';
                        }
                        @endphp
                        
                        
                        <div class="row border-bottom pb-2 pt-2">
                        <span class="col-12 col-sm-6 cart__subtotal-title"><strong>Grand Total</strong></span>
                        <span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right"><span class="money">{{ $currency }}{{ $finaltot }}</span></span> 
                        </div>
                        <div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>
                        <a href="{{ route('checkout') }}" id="cartCheckout" class="btn btn--small-wide checkout">Proceed To Checkout</a>
                    </div>
                </div>
            @else  
                <table style="height: 100px;">
                    <tr>
                        <td colspan="3" class="text-left">Your Cart is currently empty, we recommend checking out our latest products!.</td>
                        <td colspan="3" class="text-right">
                            <a href="{{ route('home') }}" class="btn btn-secondary btn--small cart-continue">Continue shopping</a>   
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('fassets/js/vendor/photoswipe.min.js') }}"></script>
    <script src="{{ asset('fassets/js/vendor/photoswipe-ui-default.min.js') }}"></script>
    <script>
        $(document).ready(function(){ 
            $('#clearcart').on('click', function(){
                $.ajax({
                    method: "POST",
                    url: "{{ route('cartdestroy') }}",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $("#CartCount").val(0);
                            setTimeout(function() {
                                location.reload();  // Reload the page
                            }, 3000);
                        }                            
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('.rmove_itms').on('click', function(){
                var idx = $(this).attr('data');
                $.ajax({
                    method: "POST",
                    url: "{{ route('removeitem') }}",
                    data: { _token: "{{ csrf_token() }}", pid: idx },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $("#CartCount").html(data.qty);
                            $(".product_"+idx).remove();
                            $(".pro_"+idx).remove();
                            $(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                            if(data.qty == 0){
                                setTimeout(function() {
                                    location.reload();  // Reload the page
                                }, 3000);
                            }
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('.qplusx').on('click', function(){
                var idx = $(this).closest('tr').attr('data');
                var qty = $('.product_'+idx+' .qty').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('plusitem') }}",
                    data: { _token: "{{ csrf_token() }}", pid: idx, qty: qty },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $(".product_"+idx+' .cart-price span.money').text("{!! $currency !!}" + data.amt);
                            //
                            $(".pro_"+idx+' .pro_qty').text("Qty: " + qty);
                            $(".pro_"+idx+' .pro_pri').text("Price: {!! $currency !!}" + data.amt);
                            $(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                            //
                            $('.cartsubtot .money').text("{!! $currency !!}" + data.totamt);
                            $('.cart__subtotal .money').text("{!! $currency !!}" + data.totamt);
                            $('.ship_val').text("{!! $currency !!}" + '0.00');
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('.qminusx').on('click', function(){
                var idx = $(this).closest('tr').attr('data');
                var qty = $('.product_'+idx+' .qty').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('minusitem') }}",
                    data: { _token: "{{ csrf_token() }}", pid: idx, qty: qty },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $(".product_"+idx+' .cart-price span.money').text("{!! $currency !!}" + data.amt);
                            //
                            $(".pro_"+idx+' .pro_qty').text("Qty: " + qty);
                            $(".pro_"+idx+' .pro_pri').text("Price: {!! $currency !!}" + data.amt);
                            $(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                            //
                            $('.cartsubtot .money').text("{!! $currency !!}" + data.totamt);
                            $('.cart__subtotal .money').text("{!! $currency !!}" + data.totamt);
                            $('.ship_val').text("{!! $currency !!}" + '0.00');
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //addcoupon
            $('.removecoupon').hide(); $('.syscoupon').hide();
            if($('#couponx').val() != "") { $('.removecoupon').show(); $('.syscoupon').show(); }
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
                            $('.couponxd').html('Discount: ' + data.htmlx);
                            $('.couponxv').text("{!! $currency !!}" +  data.discount + '.00');
                            $('.cart__subtotal .money').text("{!! $currency !!}" + data.ftot + '.00');
                            $('.removecoupon').show();
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
                            $('.cart__subtotal .money').text("{!! $currency !!}" + data.ftot);
                            $('.syscoupon').hide();
                            $('.removecoupon').hide();
                        } else {
                            flasher.warning(data.message);
                        }
                    },
                    error: function(data){ console.log(data); }
                });
            });
            // 
            $('.countryid').on('change', function(){
                var idx = $(this).val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('getstate') }}",
                    data: { _token: "{{ csrf_token() }}", idx: idx },
                    success: function(data){  $('.stateid').empty().append('');
                        $('.stateid').append(data);
                    },
                    error: function(data){ console.log(data); }
                });
            });
            // get-rates
            $('.shipbox').hide();
            $('.get_rates').on('click', function(){ $('.ship_val').text("{!! $currency !!}" + '0.00');
                var cid = $('.countryid').val();
                var sid = $('.stateid').val();
                var zid = $('.zip_id').val();
                $.ajax({
                    method: "POST",
                    url: "{{ route('shiprate') }}",
                    data: { _token: "{{ csrf_token() }}", cid: cid, sid: sid, zid: zid },
                    success: function(data){  
                        if(data == 0) {
                            $('.ship_val').text("Free shipping");
                        } else {
                            $('.ship_val').text("{!! $currency !!}" + data + '.00');
                        }
                        $('.shipbox').show();
                    },
                    error: function(data){ console.log(data); }
                });
            });
        });
    </script>
@endpush    