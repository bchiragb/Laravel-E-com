@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Change address details</h1></div>
              </div>
        </div>
        @php
            $namex = auth()->user()->name;
            $fname = explode(' ', $namex);
        @endphp
       
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="prFeatures">
               <div class="row sprow">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10">
                        <div class="display-table">
                            <div class="cart style2">
                                <div class="alert alert-info text-uppercase" role="alert">Only add 3 address in billing and shipping address</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-2">
                        <a class="btn" href="{{ route('add_address') }}">Add Addresss</a>
                    </div>                
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col offset-md-3x">
                	<div class="mb-4">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
                            <div id="accordionExample">
                                <h2 class="title h2">Billing Address</h2>
                                @php $a = 1; 
                                foreach($bill_add as $bill){
                                    $countnm = DB::table('countries')->where('id', $bill->country_id)->first();
                                    $statenm = DB::table('states')->where('id', $bill->state_id)->first();

                                    echo '
                                        <div class="faq-body">
                                            <h4 class="panel-title collapsed" data-toggle="collapse" data-target="#collapse'.$a.'" aria-expanded="false" aria-controls="collapseOne">'.$a.'. '.$bill->uniquename.'</h4>
                                            <div id="collapse'.$a.'" class="panel-content collapse" data-parent="#accordionExample">
                                                <div class="table-responsive-sm order-table"> 
                                                    <table class="bg-white table table-bordered table-hover text-center">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>First Name</b></td>
                                                                <td><b>Last Name</b></td>
                                                                <td><b>Address</b></td>
                                                                <td><b>Apartment, suite, etc. (optional)</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td>'.$bill->firstname.'</td>
                                                                <td>'.$bill->lastname.'</td>
                                                                <td>'.$bill->address_1.'</td>
                                                                <td>'.$bill->address_2.'</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Country</b></td>
                                                                <td><b>Region / State</b></td>
                                                                <td><b>City</b></td>
                                                                <td><b>Post Code</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td>'.$countnm->name.'</td>
                                                                <td>'.$statenm->name.'</td>
                                                                <td>'.$bill->city.'</td>
                                                                <td>'.$bill->zipcode.'</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><a href="'.route('show.address', [$bill->id]).'" class="btn"">Edit</a></td>
                                                                <td colspan="2">
                                                                    <form action="'.route('delete.address', [$bill->id]).'" method="POST">
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                                                        <button type="submit" class="btn">Delete</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                $a++; }
                                @endphp

                                <h2 class="title h2">Shipping Address</h2>
                                @php $a = 1; 
                                foreach($ship_add as $ship){
                                    $countnm = DB::table('countries')->where('id', $ship->country_id)->first();
                                    $statenm = DB::table('states')->where('id', $ship->state_id)->first();

                                    echo '
                                        <div class="faq-body">
                                            <h4 class="panel-title collapsed" data-toggle="collapse" data-target="#collapsess'.$a.'" aria-expanded="false" aria-controls="collapseOne">'.$a.'. '.$ship->uniquename.'</h4>
                                            <div id="collapsess'.$a.'" class="panel-content collapse" data-parent="#accordionExample">
                                                <div class="table-responsive-sm order-table"> 
                                                    <table class="bg-white table table-bordered table-hover text-center">
                                                        <tbody>
                                                            <tr>
                                                                <td><b>First Name</b></td>
                                                                <td><b>Last Name</b></td>
                                                                <td><b>Address</b></td>
                                                                <td><b>Apartment, suite, etc. (optional)</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td>'.$ship->firstname.'</td>
                                                                <td>'.$ship->lastname.'</td>
                                                                <td>'.$ship->address_1.'</td>
                                                                <td>'.$ship->address_2.'</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Country</b></td>
                                                                <td><b>Region / State</b></td>
                                                                <td><b>City</b></td>
                                                                <td><b>Post Code</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td>'.$countnm->name.'</td>
                                                                <td>'.$statenm->name.'</td>
                                                                <td>'.$ship->city.'</td>
                                                                <td>'.$ship->zipcode.'</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><a href="'.route('show.address', [$ship->id]).'" class="btn"">Edit</a></td>
                                                                <td colspan="2">
                                                                    <form action="'.route('delete.address', [$ship->id]).'" method="POST">
                                                                        <input type="hidden" name="_method" value="DELETE">
                                                                        <input type="hidden" name="_token" value="'.csrf_token().'">
                                                                        <button type="submit" class="btn">Delete</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                $a++; }
                                @endphp
                            </div>
                        </div>
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
.alert {
    padding: .55rem 1.25rem;
}   
.order-table .table td {
    width: 20%;
} 
.sprow {
    margin-right: 0px !important;
    margin-left: 0px !important;
}  
</style>

@endsection