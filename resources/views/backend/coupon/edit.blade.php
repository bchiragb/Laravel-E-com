@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Coupon Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Coupon</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                      <div class="form-group col-3">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control upparcas" value="{{ $coupon->code }}">
                      </div>
                      <div class="form-group col-6">
                        <label>Description</label>
                        <input type="text" name="desc" class="form-control" value="{{ $coupon->desc }}">
                      </div>
                      <div class="form-group col-3">
                        <label>Quantity</label>
                        <input type="text" name="qty" class="form-control" value="{{ $coupon->qty }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option {{ $coupon->type == 0 ? 'selected' : '' }} value="0">Amount</option>
                            <option {{ $coupon->type == 1 ? 'selected' : '' }} value="1">Percentage</option>
                        </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Amount</label>
                        <input type="text" name="amt" class="form-control" value="{{ $coupon->amt }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Per User Limit</label>
                        <input type="text" name="limit" class="form-control" value="{{ $coupon->limit }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Start Date</label>
                        <input type="text" name="stdt" class="form-control datepicker datepicker1" value="{{ $coupon->stdt }}">
                      </div>
                      <div class="form-group col-4">
                        <label>End Date</label>
                        <input type="text" name="eddt" class="form-control datepicker datepicker2" value="{{ $coupon->eddt }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option {{ $coupon->status == 1 ? 'selected' : '' }} value="1">Active</option>
                            <option {{ $coupon->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
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

<link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<script src="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  
@endpush