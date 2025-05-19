@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Attribute Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Attribute</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.attribute.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.attribute.update', $attribute->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $attribute->name }}">
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                        <label>Order</label>
                        <input type="text" name="order" class="form-control" value="{{ $attribute->order }}">
                      </div>
                      <div class="form-group col-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                          <option value="1" {{ $attribute->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $attribute->status == 0 ? 'selected' : '' }}>Inactive</option>
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

  <link rel="stylesheet" href="assets/modules/summernote/summernote-bs4.css">
  <script src="assets/modules/summernote/summernote-bs4.js"></script>
  
@endpush