@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Order History</h1></div>
              </div>
        </div>
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="prFeatures">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 sm-margin-30px-bottom">
                    @php
                        $order = DB::table('orders')->where('customer_id', Auth::id())->get();
                        if($order->count() > 0){
                            echo '
                                <div class="create-ac-content bg-light-gray padding-20px-all">
                                <div id="sizechart">
                                    <table class="table-hover">
                                    <tbody>
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                ';
                                foreach($order as $ord){
                                    $cancel = '<a class="btn btn--secondary get-rates" href="'.route('cancel.order', [$ord->order_id]).'">Cancel Order</a>';
                                    echo '
                                        <tr>
                                            <td>'.$ord->order_id.'</td>
                                            <td>'.date('F j, Y', strtotime($ord->order_date)).'</td>
                                            <td>Completed</td>
                                            <td>'.$currency.$ord->total_amount.'</td>
                                            <td><a class="btn btn--secondary get-rates" href="'.route('show.order', [$ord->order_id]).'">View</a></td>
                                        </tr>
                                    ';
                                }
                                echo'</tbody>
                                    </table>
                                </div>
                            </div>
                            ';

                            
                        } else {
                            echo '
                                <div class="alert alert-info text-uppercase" role="alert">
                                    <i class="icon fa fa-ban icon-large"></i> &nbsp;Your order history is empty because no orders have been placed.
                                </div>
                            ';
                        }
                    @endphp
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn {
    padding: 2px 10px !important;
}   
#sizechart table tr th, #sizechart table tr td {
    font-size: 14px; 
}
#sizechart {
    max-width: 900px;    
}    
</style>

@endsection

@push('scripts')


@endpush