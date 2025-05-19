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
                <h4>All SEO Data</h4> 
                <div class="card-header-action">
                    <a href="{{ route('admin.seo.create') }}" class="btn btn-primary"><i class='fas fa-plus'></i> Add New</a>
                </div>  
              </div>
              <div class="card-body">
                <input type="hidden" id="rloadurl" value="{{ route('admin.seo.index') }}" />
                <table class="table dataTable no-footer" id="productsTable">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Type</th>
                          <th>URL</th>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection



@push('scripts')
    <script>
      $(document).ready(function() {
          $('#productsTable').DataTable({
              processing: true,
              serverSide: true,
              ajax: '{{ route('seo.data') }}', 
              columns: [
                  { data: 'id', name: 'id' },
                  { data: 'Type', name: 'Type' },
                  { data: 'URL', name: 'URL' },
                  { data: 'title', name: 'title' },
                  { data: 'desc', name: 'desc' },
                  { data: 'actions', name: 'actions', orderable: false, searchable: false }
              ]
          });
      });
  </script>
@endpush