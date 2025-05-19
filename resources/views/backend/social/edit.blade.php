@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Social Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Social Account</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.social.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.social.update', $social->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                      <div class="form-group">
                          <label>Icon</label><br>
                          <button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info" role="iconpicker" name="icon"  data-icon="{{ $social->val2 }}"></button>
                      </div>
                      <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $social->val1 }}">
                      </div>
                      <div class="row">
                        <div class="form-group col-6">
                          <label>Link</label>
                          <input type="text" name="link" class="form-control" value="{{ $social->val3 }}">
                        </div>
                        <div class="form-group col-6">
                          <label>Status</label>
                          <select id="" name="status" class="form-control">
                              <option value="1" {{ $social->val5 == 1 ? 'selected' : '' }}>Active</option>
                              <option value="0" {{ $social->val5 == 0 ? 'selected' : '' }}>Inactive</option>
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

  <link rel="stylesheet" href="http://ecommerce.test/backend/assets/css/bootstrap-iconpicker.min.css">
  <script src="http://ecommerce.test/backend/assets/js/bootstrap-iconpicker.bundle.min.js"></script>

@endpush