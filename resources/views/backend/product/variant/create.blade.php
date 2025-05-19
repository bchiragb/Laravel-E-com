@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Create New Variant - {{ $product->title }}</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product-variant.index', ['product' => request()->product]) }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product-variant.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <input type="hidden" name="parent_product" value="{{ request()->product }}" />
                      @foreach ($m_attr as $mattr)
                        <div class="form-group col-3">
                          <label id="{{ $mattr->id }}">{{ $mattr->name }}</label>
                          @php
                            $modelName = \App\Models\Attribute::where('status', '1')->where('parent', $mattr->id)->orderBy('name', 'asc')->get();
                          @endphp
                          <select class="form-control select2" name="{{ $mattr->slug }}">
                            <option value="0">Select</option>
                            @foreach ($modelName as $attr)
                              <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endforeach
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-2">
                        <label>Regular Price</label>
                        <input type="input" name="rprice" class="form-control" value="0">
                      </div>
                      <div class="form-group col-2">
                        <label>Sale Price</label>
                        <input type="input" name="sprice" class="form-control" value="0">
                      </div>
                      <div class="form-group col-2">
                        <label>Stock</label>
                        <input type="input" name="stock" class="form-control" value="0">
                      </div>
                      <div class="form-group col-3">
                        <label>SKU</label>
                        <input type="input" name="sku" class="form-control" placeholder="Autogenrated">
                      </div>
                      <div class="form-group col-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>
  <style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #6777ef !important;
      border: 1px solid #6777ef !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: #6777ef !important;
      color: #fff !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
      border: 1px solid #e4e6fc !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 42px !important;
    }
  </style>
@endsection

@push('scripts')

  <link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
  <script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>

  <script>
    $(document).ready(function(){
      
    });
  </script>

@endpush