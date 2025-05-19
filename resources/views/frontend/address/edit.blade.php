@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div id="MainContent" class="main-content" role="main">
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper"><h1 class="page-width">Edit address</h1></div>
              </div>
        </div>
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="prFeatures">
                <div class="row sprow">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-11">
                        <div class="display-table">
                            <div class="cart style2">
                                <div class="alert alert-info text-uppercase" role="alert">(*) All field are compulsary</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-1 text-right">
                        <a class="btn" href="{{ route('address') }}">Back</a>
                    </div>                
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 sm-margin-30px-bottom">
                    <div class="create-ac-content bg-light-gray padding-20px-all">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{ route('edit.address', [$addressx->id]) }}" id="CustomerLoginForm" accept-charset="UTF-8" class="contact-form">	
                            @csrf
                            @method('PUT')
                            <fieldset>
                                <div class="row">
                                    <div class="form-group col-md-4 col-lg-4 col-xl-4 required">
                                        <label for="input-firstname">Unique Name <span class="required-f">*</span></label>
                                        <input name="uniquename" id="input-uniquename" type="text" value="{{ $addressx->uniquename }}">
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4 col-xl-4 required">
                                        <label for="input-firstname">First Name <span class="required-f">*</span></label>
                                        <input name="firstname" id="input-firstname" type="text" value="{{ $addressx->firstname }}">
                                    </div>
                                    <div class="form-group col-md-4 col-lg-4 col-xl-4 required">
                                        <label for="input-lastname">Last Name <span class="required-f">*</span></label>
                                        <input name="lastname" id="input-lastname" type="text" value="{{ $addressx->lastname }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-address-1">Address <span class="required-f">*</span></label>
                                        <input name="address_1" id="input-address-1" type="text" value="{{ $addressx->address_1 }}">
                                    </div>
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-address-1">Apartment, suite, etc. (optional)<span class="required-f">*</span></label>
                                        <input name="address_2" id="input-address-1" type="text" value="{{ $addressx->address_2 }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-country">Country <span class="required-f">*</span></label>
                                        <select class="form-control select2 countryid" name="country_id">
                                            <option value="0"> -- </option>
                                            @foreach ($country as $coun)
                                              <option value="{{ $coun->id }}" @if($coun->id == $addressx->country_id) selected @endif >{{ $coun->name }}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-country">Region / State <span class="required-f">*</span></label>
                                        <select class="form-control select2 state_id" name="state_id">
                                            <option value="{{ $state->id }}"> {{ $state->name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-city">City <span class="required-f">*</span></label>
                                        <input name="city" id="city" type="text" value="{{ $addressx->city }}">
                                    </div>
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                        <label for="input-city">Post Code <span class="required-f">*</span></label>
                                        <input name="zipcode" id="zipcode" type="text" value="{{ $addressx->zipcode }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-lg-12 col-xl-12">
                                        <input type="submit" class="btn mb-3" value="Update Address">
                                    </div>                        
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #6777ef !important;
      border: 1px solid #6777ef !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: #6777ef !important;
      color: #fff !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple {
      border: 1px solid #e4e6fc !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 42px !important;
    }
    .alert {
        padding: 8px 25px;
    }    
    .sprow {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }   
    .form-control {
        font-size: 14px;
    } 
  </style>
@endsection

@push('scripts')

<link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
<script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>
<script>
    $(document).ready(function(){ 
        $('.countryid').on('change', function(){
            var idx = $(this).val();
            $.ajax({
                method: "POST",
                url: "{{ route('getstate') }}",
                data: { idx: idx, _token: "{{ csrf_token() }}" },
                success: function(data){ $('.state_id').empty().append('<option value="0">All</option>');
                  $('.state_id').append(data);
                },
                error: function(data){ console.log(data); }
            });
        });
    });
</script>

@endpush