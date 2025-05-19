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
                <h4>Create New Coupon</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.coupon.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-3">
                        <label>Code</label>
                        <input type="input" name="code" class="form-control upparcas">
                      </div>
                      <div class="form-group col-6">
                        <label>Description</label>
                        <input type="input" name="desc" class="form-control">
                      </div>
                      <div class="form-group col-3">
                        <label>Quantity</label>
                        <input type="input" name="qty" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="0">Amount</option>
                            <option value="1">Percentage</option>
                        </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Amount</label>
                        <input type="input" name="amt" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>Per User Limit</label>
                        <input type="input" name="limit" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Start Date</label>
                        <input type="input" name="stdt" class="form-control datepicker">
                      </div>
                      <div class="form-group col-4">
                        <label>End Date</label>
                        <input type="input" name="eddt" class="form-control datepicker">
                      </div>
                      <div class="form-group col-4">
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

@endsection

@push('scripts')

<link rel="stylesheet" href="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
<script src="{{ asset('bassets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

@endpush
