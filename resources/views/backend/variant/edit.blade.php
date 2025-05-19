@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1> {{ $pdata->name }} - Variant Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit  {{ $pdata->name }} Variant</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.variant.index', $pdata->id) }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.variant.update', $variant->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="parent" class="form-control" value="{{ $pdata->id }}">
                    <div class="row">
                      <div class="form-group col-6">
                          <label>Name</label> 
                          <input type="text" name="name" class="form-control" value="{{ $variant->name }}">
                      </div>
                      <div class="form-group col-6">
                        <label>Value</label> 
                        @if($pdata->id == 2)
                          <input type="text" name="val" class="form-control colorpickerinput" value="{{ $variant->value }}">
                        @else
                          <input type="text" name="val" class="form-control"  value="{{ $variant->value }}">
                        @endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                        <label>Order</label>
                        <input type="text" name="order" class="form-control"  value="{{ $variant->order }}">
                      </div>
                      <div class="form-group col-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>Inactive</option>
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

@endsection

@push('scripts')

  <link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }} ">
  <script src="{{ asset('bassets/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
  <script>
    $(".colorpickerinput").colorpicker({
      format: 'hex',
      component: '.input-group-append',
    });
  </script>

@endpush