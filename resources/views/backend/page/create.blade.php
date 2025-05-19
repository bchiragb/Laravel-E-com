@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Page Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Create New Page</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.page.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.page.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                      <label>Content</label>
                      <textarea class="summernote" name="content" ></textarea>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select id="" name="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                      </select>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>SEO - Title</label>
                        <input type="input" name="seo_titl" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>SEO - Keyword</label>
                        <input type="input" name="seo_keyw" class="form-control">
                      </div>
                      <div class="form-group col-4">
                        <label>SEO - Canonical</label>
                        <input type="input" name="seo_cano" class="form-control">
                      </div>
                      <div class="form-group col-12">
                        <label>SEO - Description</label>
                        <textarea name="seo_desc" class="form-control"></textarea>
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

<link rel="stylesheet" href="{{ asset('bassets/modules/summernote/summernote-bs4.css') }}">
<script src="{{ asset('bassets/modules/summernote/summernote-bs4.js') }}"></script>

@endpush
