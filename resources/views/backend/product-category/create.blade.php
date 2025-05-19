@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Category Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Create New Category</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product-category.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product-category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <label>Image</label>
                      <input type="file" name="image" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <textarea name="desc" class="form-control">{{ old('desc') }}</textarea>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                          @php
                            $Categories = \App\Models\ProductCategory::where('status', 1)->get();
                          @endphp
                          <label>Parent Category</label>
                          <select name="parentcat" class="form-control">
                            <option value="0">select</option>
                            @foreach ($Categories as $Category)
                            <option value="{{$Category->id}}">{{ $Category->name }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Show on Homepage?</label>
                        <select name="featured" class="form-control">
                          <option value="0">No</option>  
                          <option value="1">Yes</option>
                        </select>
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