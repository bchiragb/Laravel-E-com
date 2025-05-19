@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Gallery</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Product: {{ $product->title }}</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Back</a>
                </div>  
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product-imgs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Image <code>(Multiple Image Supportd)</code></label>
                        <input type="file" name="image[]" class="form-control" multiple="multiple" />
                        <input type="hidden" value="{{ $product->id }}" name="product" />
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section">
        <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>All Products Image</h4>  
                </div>
                <div class="card-body">
                  <input type="hidden" id="stsurl" value="{{ route('admin.product.chg_sts') }}" />
                  <input type="hidden" id="rloadurl" value="{{ route('admin.product.index') }}" />
                  {{ $dataTable->table() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush