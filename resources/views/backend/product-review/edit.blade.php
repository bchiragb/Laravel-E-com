@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Review Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Review</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.review.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.review.update', $review->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                      <div class="form-group col-10">
                        <label>Title</label>
                        <input type="input" name="title" class="form-control upparcas" value="{{ $review->title }}">
                      </div>
                      <div class="form-group col-2">
                        <label>Star</label>
                        <select name="star" class="form-control">
                            <option value="5" {{ $review->star == 5 ? 'selected' : '' }}>5</option>
                            <option value="4" {{ $review->star == 4 ? 'selected' : '' }}>4</option>
                            <option value="3" {{ $review->star == 3 ? 'selected' : '' }}>3</option>
                            <option value="2" {{ $review->star == 2 ? 'selected' : '' }}>2</option>
                            <option value="1" {{ $review->star == 1 ? 'selected' : '' }}>1</option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-12">
                        <label>Review</label>
                        <textarea name="review" class="form-control">{{ $review->desc }}</textarea>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-8">
                        <label>Product</label>
                        <select class="form-control select2" name="proid">
                          @foreach ($product as $pro)
                            <option value="{{ $pro->id }}" {{ $review->product_id == $pro->id ? 'selected' : '' }}>{{ $pro->title }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Date</label>
                        <input type="text" name="datex" class="form-control datepicker" value="{{ $review->pdate }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Name</label>
                        <input type="input" name="name" class="form-control" value="{{ $review->name }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Email</label>
                        <input type="input" name="email" class="form-control" value="{{ $review->email }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Inactive</option>
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

<link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<script src="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
<script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>

@endpush
