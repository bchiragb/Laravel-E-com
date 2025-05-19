@extends('backend.layout.master')

@section('admin_body_content')

<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Profile</h1>
      </div>
      <div class="section-body">
        <h2 class="section-title">Hi, {{ Auth::user()->name }}</h2>
        <div class="row mt-sm-4">
          <div class="col-12 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <form method="post" action="{{ route('admin.aeditprofile') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    @php
                        $name = explode(' ', Auth::user()->name);
                        $first_name = $name[0];
                        $last_name = $name[1];
                    @endphp 
                  <div class="card-body">
                    
                  
                    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" />
                    <script>
                        @foreach ($errors->all() as $error)
                            toastr.error("{{$error}}");
                        @endforeach
                    </script> --}}


                    {{-- <script src="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.min.js"></script>
                    <link rel="stylesheet" href="https://www.unpkg.com/@flasher/flasher@1.3.1/dist/flasher.css" />
                    <script type="text/javascript"> //npm i @flasher/flasher
                        flasher.error("Oops! Something went wrong!");
                        flasher.warning("Are you sure you want to proceed ?");
                        flasher.success("Data has been saved successfully!");
                        flasher.info("Welcome back");
                    </script> --}}
                   
                      @push('scripts')
                        <script>
                          @foreach ($errors->all() as $error)
                            //flasher.info("{{$error}}");
                          @endforeach      
                        </script>
                      @endpush

                      <div class="row">                               
                        <div class="form-group col-md-6 col-12">
                          <label>First Name</label>
                          <input type="text" class="form-control" name="first_name" value="{{ $first_name }}" required="">
                          <div class="invalid-feedback">
                            Please fill in the first name
                          </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Last Name</label>
                          <input type="text" class="form-control" name="last_name" value="{{ $last_name }}" required="">
                          <div class="invalid-feedback">
                            Please fill in the last name
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6 col-12">
                          <label>Email</label>
                          <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly="readonly" required="">
                          <div class="invalid-feedback">
                            Please fill in the email
                          </div>
                        </div>
                        <div class="form-group col-md-6 col-12">
                          <label>Mobile (Add county code before number)</label>
                          <input type="tel" class="form-control" name="mobile" value="{{ Auth::user()->contact }}">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <label>Current Picture</label>
                            @if (Auth::user()->img != "")
                                <img src="{{ asset(Auth::user()->img) }}" name="pro_pic" class="" style="width: 250px;"/>
                            @else
                                <img src="{{ asset('bassets/img/avatar/avatar-1.png') }}" name="pro_pic" class="" style="width: 250px;"/>
                            @endif
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Upload Picture</label>
                            <div class="custom-file">
                                <input type="file" class="form-control" name="profile_pic">
                            </div>
                        </div>
                      </div> 
                  </div>
                  <div class="card-footer text-right">
                    <button class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
          </div>
          <div class="col-12 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Change Password</h4>
                </div>
                <form method="post" action="{{ route("admin.changepassword") }}" class="needs-validation" novalidate="">
                    @csrf
                    <div class="card-body">
                        <div class="row">                               
                          <div class="form-group col-md-12 col-12">
                            <label>Current Password <span style="color:red">*</span></label>
                            <input type="Password" class="form-control" name="current_password" required="">
                            @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-12 col-12">
                            <label>New Password <span style="color:red">*</span></label>
                            <input type="Password" class="form-control" name="new_password" required="">
                            @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>

@endsection