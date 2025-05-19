@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Review Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>All Review</h4> 
                <div class="card-header-action">
                  @if(request()->has('product'))
                    <a href="{{ route('admin.review.create', ['product' => request()->query('product')]) }}" class="btn btn-primary"><i class='fas fa-plus'></i> Add New</a>
                  @else
                    <a href="{{ route('admin.review.create') }}" class="btn btn-primary"><i class='fas fa-plus'></i> Add New</a>
                  @endif
                </div>  
              </div>
              <div class="card-body">
                <input type="hidden" id="stsurl" value="{{ route('admin.review.chg_sts') }}" />
                <input type="hidden" id="rloadurl" value="{{ route('admin.review.index') }}" />
                {{ $dataTable->table() }}

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush