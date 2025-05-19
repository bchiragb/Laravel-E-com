@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>FAQ Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Create New Faq</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.faq.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="question" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Answer</label>
                      <textarea class="summernote" name="answer" ></textarea>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select id="" name="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                      </select>
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

  <link rel="stylesheet" href="assets/modules/summernote/summernote-bs4.css">
  <script src="assets/modules/summernote/summernote-bs4.js"></script>

@endpush
