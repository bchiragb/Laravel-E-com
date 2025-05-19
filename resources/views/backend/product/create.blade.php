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
                <h4>Create New Product</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Main Image 1</label>
                        <input type="file" name="img1" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>Main Image 2</label>
                        <input type="file" name="img2" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>Video link</label>
                        <input type="input" name="video" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-12">
                        <label>Name</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Category</label>
                        <select class="form-control select2" name="category"  multiple="">
                          @foreach ($cat as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Brand</label>
                        <select name="brand" class="form-control">
                          <option value="">Select</option>
                          @foreach ($brand as $brd)
                            <option value="{{ $brd->id }}">{{ $brd->name }}</option>
                          @endforeach
                        </select> 
                      </div>
                      <div class="form-group col-4">
                        <label>Tag </label>
                        <select name="tag[]" class="form-control select2" multiple="">
                            <option value="1">New</option>
                            <option value="2">Hot</option>
                            <option value="3">Popular</option>
                        </select> 
                        <small id="passwordHelpBlock" class="form-text text-muted">sale tag automatically added, when sale price entered</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <textarea class="form-control" name="desc">{{ old('desc') }} </textarea>
                    </div>
                    <div class="form-group">
                      <label>Additional Info / Care Guide / Instructions</label>
                      <textarea class="form-control summernote" name="info">{{ old('info') }} </textarea>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Start Date</label>
                        <input type="input" name="stdt" class="form-control datepicker">
                        <small id="passwordHelpBlock" class="form-text text-muted">sale page not count same date in start date & end date field</small>
                      </div>
                      <div class="form-group col-4">
                        <label>End Date</label>
                        <input type="input" name="eddt" class="form-control datepicker">
                        <small id="passwordHelpBlock" class="form-text text-muted">start date & end date product show in sale page</small>
                      </div>
                      <div class="form-group col-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-2">
                        <label>Product Type</label>
                        <select name="ptype" class="form-control ptypeselect">
                            <option value="1">Simple</option>
                            <option value="2">Variant</option>
                        </select> 
                      </div>
                      <div class="form-group col-2 simbox">
                        <label>Regular Price</label>
                        <input type="input" name="rprice" class="form-control">
                      </div>
                      <div class="form-group col-2 simbox">
                        <label>Sale Price</label>
                        <input type="input" name="sprice" class="form-control">
                      </div>
                      <div class="form-group col-3 simbox">
                        <label>Stock</label>
                        <input type="input" name="stock" class="form-control">
                      </div>
                      <div class="form-group col-3 simbox">
                        <label>SKU</label>
                        <input type="input" name="sku" class="form-control" placeholder="Autogenrated">
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>SEO - Keyword</label>
                        <input type="input" name="seo_keyw" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>SEO - Title</label>
                        <input type="input" name="seo_titl" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>SEO - Canonical</label>
                        <input type="input" name="seo_cano" class="form-control">
                      </div>
                      <div class="form-group col-12">
                        <label>SEO - Description</label>
                        <textarea name="seo_desc" class="form-control"></textarea>
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
    });
  </script>
@endpush
