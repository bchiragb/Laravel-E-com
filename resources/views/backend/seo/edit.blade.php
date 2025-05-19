@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>SEO Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Seo</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.seo.index') }}" class="btn btn-primary">Back</a>
                </div> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.seo.update', $seo->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Type</label>
                        <input type="hidden" class="currentval" value="{{ $seo->type }}" />
                        <select name="seo_type" disabled class="seo_type form-control">
                            <option value="0" {{ $seo->type == 0 ? 'selected' : '' }}>Custom URL</option>
                            <option value="1" {{ $seo->type == 1 ? 'selected' : '' }}>Product</option>
                            <option value="2" {{ $seo->type == 2 ? 'selected' : '' }}>Page</option>
                        </select>
                      </div>
                      <div class="form-group col-8 seo_name0">
                        <label>Custom Name</label>
                        <input type="input" disabled name="seo_name0" class="form-control" value="{{ $seo->url }}">
                      </div>
                      <div class="form-group col-8 diseble seo_name1">
                        <label>Product Name</label>
                        <select name="seo_name1" disabled class="form-control">
                          @foreach ($product as $pro)
                            <option value="{{ '/product/'.$pro->slug }}" {{ '/product/'.$pro->slug == $seo->url ? 'selected' : '' }}>{{ $pro->title }}</option>
                          @endforeach
                        </select> 
                      </div>
                      <div class="form-group col-8 diseble seo_name2">
                        <label>Page Name</label>
                        <select name="seo_name2" disabled class="form-control">
                          @foreach ($page as $page)
                            <option value="{{ '/'.$page->slug }}"{{ '/'.$page->slug == $seo->url ? 'selected' : '' }}>{{ $page->title }}</option>
                          @endforeach
                        </select> 
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-12">
                        <label>SEO Title</label>
                        <input type="input" name="seo_title" class="form-control" value="{{ $seo->title }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-12">
                        <label>SEO Desc</label>
                        <textarea name="seo_desc" class="form-control">{{ $seo->desc }}</textarea>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                        <label>SEO Keyword</label>
                        <input type="input" name="seo_keyw" class="form-control" value="{{ $seo->keyword }}">
                      </div>
                      <div class="form-group col-6">
                        <label>SEO Canonical</label>
                        <input type="input" name="seo_cano" class="form-control" value="{{ $seo->canonical }}">
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
  <style>.diseble { display: none } </style>
  @endsection
  
  @push('scripts')
    <link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
    <script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>
  
    <script>
      $(document).ready(function(){
        $('.seo_type').on('change', function(){
            let val = $(this).val();
            if($(this).val() == 1) {
              $('.seo_name0').addClass('diseble');
              $('.seo_name1').removeClass('diseble');
              $('.seo_name2').addClass('diseble');
            } else if($(this).val() == 2) {
              $('.seo_name0').addClass('diseble');
              $('.seo_name1').addClass('diseble');
              $('.seo_name2').removeClass('diseble');
            } else {
              $('.seo_name0').removeClass('diseble');
              $('.seo_name1').addClass('diseble');
              $('.seo_name2').addClass('diseble');
            }
        });
        //
        var setvalx = $('.currentval').val();
        if(setvalx == 1) {
          $('.seo_name0').addClass('diseble');
          $('.seo_name1').removeClass('diseble');
          $('.seo_name2').addClass('diseble');
        } else if(setvalx == 2) {
          $('.seo_name0').addClass('diseble');
          $('.seo_name1').addClass('diseble');
          $('.seo_name2').removeClass('diseble');
        } else {
          $('.seo_name0').removeClass('diseble');
          $('.seo_name1').addClass('diseble');
          $('.seo_name2').addClass('diseble');
        }

      });
    </script>
  @endpush