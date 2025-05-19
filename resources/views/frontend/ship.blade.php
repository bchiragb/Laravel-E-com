@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Shipping Details</h1></div>
              </div>
        </div>
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="prFeatures">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 sm-margin-30px-bottom">
                    @if($ship->count() > 0)
                        <div class="create-ac-content bg-light-gray padding-20px-all">
                            <div id="sizechart">
                                <table class="table-hover">
                                    <tbody>
                                        <tr>
                                            <th>Order</th>
                                            <th>Shipping Date</th>
                                            <th>Shipping Provider</th>
                                            <th>Shipping Tracking Code</th>
                                            <th>Shipping Link</th>
                                        </tr>
                                        @foreach ($ship as $ship)  
                                            @php
                                                $datex = date('F j, Y', strtotime($ship->sdate));
                                            @endphp                    
                                            <tr>
                                                <td><a class="btnx btn--secondaryx" href="{{ route('show.order', [$ship->order_id]) }}">#{{ $ship->order_id }}</a></td>
                                                <td>{{ $datex }}</td>
                                                <td>{{ $ship->sname }}</td>
                                                <td>{{ $ship->sno }}</td>
                                                <td><a target="_blank" class="btn btn--secondary get-rates" href="{{ $ship->surl }}">View</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else 
                        <div class="alert alert-info text-uppercase" role="alert">
                            <i class="icon fa fa-ban icon-large"></i> &nbsp;Your order history is empty because no orders have been placed.
                        </div>
                    @endif
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