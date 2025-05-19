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
                <h4>Edit Category</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.product-category.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.product-category.update', $categoryx->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Current Image - click on img for enlarge</label><br>
                            @if($categoryx->img != '')
                              <a href="{{ asset($categoryx->img) }}" target="_blank"><img src="{{ asset($categoryx->img) }}" style="width:100px; height:100px;"></a><br>
                            @else
                              Image not uploaded
                            @endif
                        </div>
                        <div class="form-group col-6">
                            <label>Upload New Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $categoryx->name }}">
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <textarea name="desc" class="form-control" rows="4" cols="50"  style="height:100px !important;">{{ $categoryx->desc }}</textarea>
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
                                <option {{ $categoryx->parent == $Category->id ? 'selected' : '' }} value="{{$Category->id}}">{{ $Category->name }}</option>
                            @endforeach
                          </select>
                      </div>
                      <div class="form-group col-4">
                        <label>Show on Homepage?</label>
                        <select name="featured" class="form-control">
                            <option value="1" {{ $categoryx->featured == 1 ? 'selected': '' }}>Yes</option>
                            <option value="0" {{ $categoryx->featured == 0 ? 'selected': '' }}>No</option>
                        </select>
                      </div>
                      <div class="form-group col-4">
                          <label>Status</label>
                          <select name="status" class="form-control">
                              <option value="1" {{ $categoryx->status == 1 ? 'selected': '' }}>Active</option>
                              <option value="0" {{ $categoryx->status == 0 ? 'selected': '' }}>Inactive</option>
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