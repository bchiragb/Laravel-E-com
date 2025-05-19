@extends('backend.layout.master')

@section('admin_body_content')

  <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>Product Page Setting</h1>
        </div>
        <div class="section-body">
          <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-12">
              <div class="card">
                  <div class="card-header">
                      <h4>Edit Basic Details</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_product_setting') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                      @csrf
                    <div class="card-body">
                        <div class="row">                               
                          <div class="form-group col-3">
                            <label>Free Shipping Amount ({{ $currency }})</label>
                            <input type="text" class="form-control" name="ship_amt" value="{{ $data1->val1 }}" required="">
                          </div>
                          <div class="form-group col-3">
                            <label>International Shipping Charge ({{ $currency }})</label>
                            <input type="text" class="form-control" name="inte_chrge" value="{{ $data1->val4 }}" required="">
                          </div>
                          <div class="form-group col-3">
                            <label>Domestic Shipping Charges ({{ $currency }})</label>
                            <input type="text" class="form-control" name="dos_chrge" value="{{ $data1->val3 }}" required="">
                          </div>
                          <div class="form-group col-3">
                            <label>Store Origin Country</label>
                            <select class="form-control select2" name="ship_count">
                              @foreach ($country as $coun)
                                <option value="{{ $coun->id }}" {{ $coun->id == $data1->val5 ? 'selected' : ''}}>{{ $coun->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group col-3">
                            <label>Estimate delivery days</label>
                            <input type="text" class="form-control" name="ship_day" value="{{ $data1->val2 }}" required="">
                          </div>
                          <div class="form-group col-3">
                            <label>Currency Name</label>
                            <input type="text" class="form-control" name="cur_nm" value="{{ $data2->val3 }}" required="">
                          </div>
                          <div class="form-group col-3">
                            <label>Currency Symbol</label>
                            <input type="text" class="form-control" name="cur_sym" value="{{ $data2->val4 }}" required="">
                          </div>
                        </div>
                        <div class="row">                               
                            <div class="form-group col-6">
                              <label>Returns policy text on prodct page</label>
                              <textarea class="form-control" name="return_txt">{{ $data2->val1 }}</textarea>
                            </div>
                            <div class="form-group col-6">
                              <label>Shipping policy text on prodct page</label>
                              <textarea class="form-control" name="ship_txt">{{ $data2->val2 }}</textarea>
                            </div>
                          </div>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
        </div>
      </section>
  </div>
  <style>
    .select2-container--default .select2-selection--single .select2-selection__rendered { margin: 5px; }
  </style>

@endsection

@push('scripts')

  <link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
  <script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>

@endpush
