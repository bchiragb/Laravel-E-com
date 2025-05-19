@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Shipping Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Shipping</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.shipping.update', $shipping->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Country</label>
                          <select class="form-control select2 countryid" name="countryid">
                            <option value="0"> Select an option </option>
                            @foreach ($country as $coun)
                              <option value="{{ $coun->id }}" {{ $shipping->countryid == $coun->id ? 'selected' : '' }}>{{ $coun->name }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group col-4">
                        <label>State</label>
                          <select class="form-control select2 stateid" name="stateid">
                              <option value=""> Select an option </option>
                              <option value="0" {{ $shipping->stateid == 0 ? 'selected' : '' }}>All</option>
                              @foreach ($state as $sate)
                                <option value="{{ $sate->id }}" {{ $shipping->stateid == $sate->id ? 'selected' : '' }}>{{ $sate->name }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group col-2">
                        <label>Rate ({{ $currency }})</label>
                        <input type="input" name="rate" class="form-control" value="{{ $shipping->rate }}">
                      </div>
                      <div class="form-group col-2">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="1" {{ $shipping->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $shipping->status == 0 ? 'selected' : '' }}>Inactive</option>
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
<script>
  $(document).ready(function(){ 
      $('.countryid').on('change', function(){
          var idx = $(this).val();
          $.ajax({
              method: "POST",
              url: "{{ route('admin.shipping.getstate') }}",
              data: { idx: idx },
              success: function(data){
                $('.stateid').empty().append('<option value="0">All</option>');
                $('.stateid').append(data);
              },
              error: function(data){ console.log(data); }
          });
      });
  });
</script>


@endpush
