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
                <h4>Edit Product</h4> 
                <div class="card-header-action">
                    <a href="{{  route('admin.product-variant.index', ['product' => request()->product]) }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product-variant.update', $pro_attr->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="parent_product" value="{{ $pro_attr->product_id }}" />
                    <div class="row">
                      @foreach ($m_attr as $mattr)
                        <div class="form-group col-3">
                          <label id="{{ $mattr->id }}">{{ $mattr->name }}</label>
                          @php
                            $modelName = \App\Models\Attribute::where('status', '1')->where('parent', $mattr->id)->orderBy('name', 'asc')->get();
                          @endphp
                          {{-- {{ $pro_attr->{$mattr->slug} }}  @php echo $mattr->slug;  echo $pro_attr->{$mattr->slug} @endphp --}}
                          <select class="form-control select2" name="{{ $mattr->slug }}">
                            <option value="0">Select</option>
                            @foreach ($modelName as $attr)
                                 <option value="{{ $attr->id }}" {{ $pro_attr->{$mattr->slug} == $attr->id ? 'selected' : '' }}>{{ $attr->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      @endforeach
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-2">
                        <label>Regular Price</label>
                        <input type="input" name="rprice" class="form-control" value="{{ $pro_attr->rprice }}">
                      </div>
                      <div class="form-group col-2">
                        <label>Sale Price</label>
                        <input type="input" name="sprice" class="form-control" value="{{ $pro_attr->sprice }}">
                      </div>
                      <div class="form-group col-2">
                        <label>Stock</label>
                        <input type="input" name="stock" class="form-control" value="{{ $pro_attr->stock }}">
                      </div>
                      <div class="form-group col-3">
                        <label>SKU</label>
                        <input type="input" name="sku" class="form-control" placeholder="Autogenrated" value="{{ $pro_attr->sku }}">
                      </div>
                      <div class="form-group col-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="1" {{ $pro_attr->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $pro_attr->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
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

  <link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
  <script src="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('bassets/modules/summernote/summernote-bs4.css') }}">
  <script src="{{ asset('bassets/modules/summernote/summernote-bs4.js') }}"></script>

  <script>
    $(document).ready(function(){
      $('.varibox').hide();
      $('.ptypeselect').on('change', function(){
          let $val = $(this).val();
          if($(this).val() == 2) {
            $('.simbox').hide();
            $('.varibox').show();
          } else {
            $('.simbox').show();
            $('.varibox').hide();
          }
      });

      if($('.ptypeselect').val() == 2) {
        $('.simbox').hide();
      }

    
    });
  </script>
@endpush