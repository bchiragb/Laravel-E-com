@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Dashboard</h1>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success"><i class="fas fa-circle"></i></div>
            <div class="card-wrap">
              <div class="card-header"><h4>Today Sell</h4></div>
              <div class="card-body">{{ $currency.$tod_amt }}</div>
            </div>
          </div>
        </div> 
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="far fa-file"></i></div>
            <div class="card-wrap">
              <div class="card-header"><h4>Total Order</h4></div>
              <div class="card-body">{{ $tot_order }}</div>
            </div>
          </div>
        </div>     
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="far fa-user"></i></div>
            <div class="card-wrap">
              <div class="card-header"><h4>Total User</h4></div>
              <div class="card-body">{{ $tot_user }}</div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-danger"><i class="far fa-newspaper"></i></div>
            <div class="card-wrap">
              <div class="card-header"><h4>Total Product</h4></div>
              <div class="card-body">{{ $tot_product }}</div>
            </div>
          </div>
        </div>
                            
      </div>
    </section>
</div>

@endsection