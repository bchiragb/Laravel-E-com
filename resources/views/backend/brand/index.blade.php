@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Product Brand Master</h1>
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>All Brand</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.brand.create') }}" class="btn btn-primary"><i class='fas fa-plus'></i> Add New</a>
                </div>  
              </div>
              <div class="card-body">
                <input type="hidden" id="stsurl" value="{{ route('admin.brand.chg_sts') }}" />
                <input type="hidden" id="rloadurl" value="{{ route('admin.brand.index') }}" />
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