@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Slider Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Slide</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.slider.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                      <div class="form-group col-6">
                          <label>Current Image</label><br>
                          <a href="{{ asset($slider->val1) }}" target="_blank">
                            <img src="{{ asset($slider->val1) }}" width="100" hegith="100">
                          </a><br>
                      </div>
                      <div class="form-group">
                          <label>Upload New Image</label><br>
                          <input type="file" name="image" class="form-control">
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" class="form-control" value="{{ $slider->val2 }}">
                    </div>
                    <div class="form-group">
                      <label>Sub-Title</label>
                      <input type="text" name="sub_title" class="form-control" value="{{ $slider->val3 }}">
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control" value="{{ $slider->val4 }}">
                      </div>
                      <div class="form-group col-6">
                        <label>Status</label>
                        <select id="" name="status" class="form-control">
                            <option value="1" {{ $slider->val5 == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $slider->val5 == 0 ? 'selected' : '' }}>Inactive</option>
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