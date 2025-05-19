@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Footer Master</h1>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Edit Menu Name</h4> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.footer.savemenu') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Footer Menu 1 Name</label>
                        <input type="text" name="fmenu1" class="form-control" value="{{ $footmenu->val1 ?? '' }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Footer Menu 2 Name</label>
                        <input type="text" name="fmenu2" class="form-control" value="{{ $footmenu->val2 ?? '' }}">
                      </div>
                      <div class="form-group col-4">
                        <label>Footer Menu 3 Name</label>
                        <input type="text" name="fmenu3" class="form-control" value="{{ $footmenu->val3 ?? '' }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Edit Menu 1 Item Links</h4> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.footer.savemenu1') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 1 Name - Footer Menu 1</label>
                        <input type="text" name="fmenu11_nm" class="form-control" value="{{ $set1[1][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 1 URL - Footer Menu 1</label>
                        <input type="text" name="fmenu11_url" class="form-control" value="{{ $set1[1][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 2 Name - Footer Menu 1</label>
                        <input type="text" name="fmenu12_nm" class="form-control" value="{{ $set1[2][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 2 URL - Footer Menu 1</label>
                        <input type="text" name="fmenu12_url" class="form-control" value="{{ $set1[2][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 3 Name - Footer Menu 1</label>
                        <input type="text" name="fmenu13_nm" class="form-control" value="{{ $set1[3][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 3 URL - Footer Menu 1</label>
                        <input type="text" name="fmenu13_url" class="form-control" value="{{ $set1[3][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 4 Name - Footer Menu 1</label>
                        <input type="text" name="fmenu14_nm" class="form-control" value="{{ $set1[4][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 4 URL - Footer Menu 1</label>
                        <input type="text" name="fmenu14_url" class="form-control" value="{{ $set1[4][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Edit Menu 2 Item Links</h4> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.footer.savemenu2') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 1 Name - Footer Menu 2</label>
                        <input type="text" name="fmenu21_nm" class="form-control" value="{{ $set2[1][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 1 URL - Footer Menu 2</label>
                        <input type="text" name="fmenu21_url" class="form-control" value="{{ $set2[1][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 2 Name - Footer Menu 2</label>
                        <input type="text" name="fmenu22_nm" class="form-control" value="{{ $set2[2][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 2 URL - Footer Menu 2</label>
                        <input type="text" name="fmenu22_url" class="form-control" value="{{ $set2[2][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 3 Name - Footer Menu 2</label>
                        <input type="text" name="fmenu23_nm" class="form-control" value="{{ $set2[3][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 3 URL - Footer Menu 2</label>
                        <input type="text" name="fmenu23_url" class="form-control" value="{{ $set2[3][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 4 Name - Footer Menu 2</label>
                        <input type="text" name="fmenu24_nm" class="form-control" value="{{ $set2[4][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 4 URL - Footer Menu 2</label>
                        <input type="text" name="fmenu24_url" class="form-control" value="{{ $set2[4][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Edit Menu 3 Item Links</h4> 
              </div>
              <div class="card-body">
                <form action="{{ route('admin.footer.savemenu3') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 1 Name - Footer Menu 3</label>
                        <input type="text" name="fmenu31_nm" class="form-control" value="{{ $set3[1][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 1 URL - Footer Menu 3</label>
                        <input type="text" name="fmenu31_url" class="form-control" value="{{ $set3[1][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 2 Name - Footer Menu 3</label>
                        <input type="text" name="fmenu32_nm" class="form-control" value="{{ $set3[2][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 2 URL - Footer Menu 3</label>
                        <input type="text" name="fmenu32_url" class="form-control" value="{{ $set3[2][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 3 Name - Footer Menu 3</label>
                        <input type="text" name="fmenu33_nm" class="form-control" value="{{ $set3[3][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 3 URL - Footer Menu 3</label>
                        <input type="text" name="fmenu33_url" class="form-control" value="{{ $set3[3][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-4">
                        <label>Link 4 Name - Footer Menu 3</label>
                        <input type="text" name="fmenu34_nm" class="form-control" value="{{ $set3[4][0] ?? '' }}">
                      </div>
                      <div class="form-group col-8">
                        <label>Link 4 URL - Footer Menu 3</label>
                        <input type="text" name="fmenu34_url" class="form-control" value="{{ $set3[4][1] ?? '' }}">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
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

@endpush