@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Attribute Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Product: {{ $product->title }}</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product-variant.create', ['product' => $product->id ]) }}" class="btn btn-primary"><i class='fas fa-plus'></i> Add New</a>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Back</a>
                </div>  
              </div>
              <div class="card-body">
                <input type="hidden" id="stsurl" value="{{ route('admin.product-variant.chg_sts') }}" />
                <input type="hidden" id="rloadurl" value="{{ route('admin.product-variant.index', ['product' => $product->id]) }}" />
                {{ $dataTable->table() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<style>
  .btn {
    line-height: 14px !important;
    padding: 4px !important;
  }
  .dataTable img {
    border: 1px solid #000;
  }
  .dropdown-menu.show {
      border: 1px solid #ccc;
      background: #f3f2f2;
  }
</style>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush