@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Order Cancelled</h1></div>
          </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                <div class="customer-box returning-customer">
                    <h3><i class="icon anm anm-times-l"></i> Your order has been cancelled</h3>
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
                        	<h5>Order No: <b>1234567890</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order Date: <b>March 8, 2025</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Order Total: <b>$123.00</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Payment Mode: <b>Cash On Delivery</b></h5>
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Subtotal: <b>$735.00</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Coupon: <b>$10.00</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Shipping: <b>Free shipping</b></h5>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        	<h5>Grand Total: <b>$1001.00</b></h5>
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
                            <tr class="cart__row border-bottom line1 cart-flex border-top">
                                <td class="cart__image-wrapper cart-flex-item">
                                    <a href="#"><img class="cart__image" src="assets/images/product-images/product-image1.jpg" alt="Elastic Waist Dress - Navy / Small"></a>
                                </td>
                                <td class="cart__meta small--text-left cart-flex-item">
                                    <div class="list-view-item__title">
                                        <a href="#">Elastic Waist Dress </a>
                                    </div>
                                    
                                    <div class="cart__meta-text">
                                        Color: Navy<br>Size: Small<br>
                                    </div>
                                </td>
                                <td class="cart__price-wrapper cart-flex-item">
                                    <span class="money">$735.00</span>
                                </td>
                                <td class="cart__update-wrapper cart-flex-item text-right">
                                    <div class="cart__qty text-center">
                                        <div class="qtyField">
                                            1
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right small--hide cart-price">
                                    <div><span class="money">$735.00</span></div>
                                </td>
                            </tr>
                            <tr class="cart__row border-bottom line1 cart-flex border-top">
                                <td class="cart__image-wrapper cart-flex-item">
                                    <a href="#"><img class="cart__image" src="assets/images/product-images/product-image3.jpg" alt="3/4 Sleeve Kimono Dress"></a>
                                </td>
                                <td class="cart__meta small--text-left cart-flex-item">
                                    <div class="list-view-item__title">
                                        <a href="#">3/4 Sleeve Kimono Dress</a>
                                    </div>
                                </td>
                                <td class="cart__price-wrapper cart-flex-item">
                                    <span class="money">$735.00</span>
                                </td>
                                <td class="cart__update-wrapper cart-flex-item text-right">
                                    <div class="cart__qty text-center">
                                        <div class="qtyField">
                                            2
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right small--hide cart-price">
                                    <div><span class="money">$735.00</span></div>
                                </td>
                            </tr>
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
                            <h5>Billing Address</h5>
                            <hr>
                            <div class="form-groupx"><label>Name: 111</label></div>
                            <div class="form-groupx"><label>Address: 2323434</label></div>
                            <div class="form-groupx"><label>Address: </label></div>
                            <div class="form-groupx"><label>Country: </label></div>
                            <div class="form-groupx"><label>State / Zone: </label></div>
                            <div class="form-groupx"><label>City: </label></div>
                            <div class="form-groupx"><label>Post Code: </label></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-6">
                        <div class="solid-border boxr">
                            <h5>Shipping Address</h5>
                            <hr>
                            <div class="form-groupx"><label>Name: 111</label></div>
                            <div class="form-groupx"><label>Address: 2323434</label></div>
                            <div class="form-groupx"><label>Address: </label></div>
                            <div class="form-groupx"><label>Country: </label></div>
                            <div class="form-groupx"><label>State / Zone: </label></div>
                            <div class="form-groupx"><label>City: </label></div>
                            <div class="form-groupx"><label>Post Code: </label></div>
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