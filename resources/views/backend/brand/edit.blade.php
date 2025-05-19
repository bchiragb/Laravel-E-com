@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Brand Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Brand</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.brand.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.brand.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Logo</label><br>
                        <img src="{{ asset($brand->logo) }}" width="100" hegith="100"><br>
                        <input type="file" name="logo" class="form-control">
                    </div>
                   <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $brand->name }}">
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                          <label>Is Feature ?</label>
                          <select id="" name="is_feature" class="form-control">
                              <option {{ $brand->is_featured == 1 ? 'selected' : '' }} value="1">Yes</option>
                              <option {{ $brand->is_featured == 0 ? 'selected' : '' }} value="0">No</option>
                          </select>
                        </div>
                      <div class="form-group col-6">
                          <label>Status</label>
                          <select id="" name="status" class="form-control">
                              <option {{ $brand->status == 1 ? 'selected' : '' }} value="1">Active</option>
                              <option {{ $brand->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
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