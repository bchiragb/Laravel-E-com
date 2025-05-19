@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Faq Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit FAQ</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.faq.update', $faq->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="question" class="form-control" value="{{ $faq->question }}">
                    </div>
                    <div class="form-group">
                      <label>Answer</label>
                      <textarea name="answer" class="summernote">{{ $faq->answer }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select id="" name="status" class="form-control">
                          <option value="1" {{ $faq->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $faq->status == 0 ? 'selected' : '' }}>Inactive</option>
                      </select>
                    </div>
                    <div class="row">
                      <div class="form-group  col-6">
                        <label>Faq Category</label>
                        <select id="" name="category" class="form-control">
                            <option value="1" {{ $faq->category == 1 ? 'selected' : '' }}>General</option>
                            <option value="2" {{ $faq->category == 2 ? 'selected' : '' }}>Product & Order</option>
                            <option value="3" {{ $faq->category == 3 ? 'selected' : '' }}>Payment & Billing</option>
                            <option value="4" {{ $faq->category == 4 ? 'selected' : '' }}>Shipping & Delivery</option>
                            <option value="5" {{ $faq->category == 5 ? 'selected' : '' }}>Returns, Replacement & Refunds</option>
                            <option value="6" {{ $faq->category == 6 ? 'selected' : '' }}>Technical Support</option>
                        </select>
                      </div>
                      <div class="form-group  col-6">
                        <label>Status</label>
                        <select id="" name="status" class="form-control">
                          <option value="1" {{ $faq->status == 1 ? 'selected' : '' }}>Active</option>
                          <option value="0" {{ $faq->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
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