@extends('backend.layout.master')

@section('admin_body_content')

  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>All Settings</h1>
        {{-- <h1>All Settings -   {{ $appName }} - {{ $MAIL_HOST }} - {{ $MAIL_HOST1 }}</h1> --}}
      </div>
      <div class="section-body">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4>Jump To {{ config('mail.post') }}</h4>
              </div>
              <div class="card-body">
                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabbox-tab1" data-toggle="tab" href="#tabbox1" role="tab" aria-controls="tabbox" aria-selected="true">Upload New Logo</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab2" data-toggle="tab" href="#tabbox2" role="tab" aria-controls="tabbox" aria-selected="false">Site Basic Details</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab3" data-toggle="tab" href="#tabbox3" role="tab" aria-controls="tabbox" aria-selected="false">Home Page - Information Box</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab4" data-toggle="tab" href="#tabbox4" role="tab" aria-controls="tabbox" aria-selected="false">Home, Product, Category Page Setting</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab5" data-toggle="tab" href="#tabbox5" role="tab" aria-controls="tabbox" aria-selected="false">Single Product Page & Shipping Setting</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab6" data-toggle="tab" href="#tabbox6" role="tab" aria-controls="tabbox" aria-selected="false">Payment Method - Paypal</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab7" data-toggle="tab" href="#tabbox7" role="tab" aria-controls="tabbox" aria-selected="false">Payment Method - Stripe</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab8" data-toggle="tab" href="#tabbox8" role="tab" aria-controls="tabbox" aria-selected="false">Payment Method - Razorpay</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab9" data-toggle="tab" href="#tabbox9" role="tab" aria-controls="tabbox" aria-selected="false">Payment Note & COD Setting</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab10" data-toggle="tab" href="#tabbox10" role="tab" aria-controls="tabbox" aria-selected="false">SMTP Mail Setting & Test Mail</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabbox-tab11" data-toggle="tab" href="#tabbox11" role="tab" aria-controls="tabbox" aria-selected="false">Popup Setting</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="tab-content no-padding" id="myTab2Content">
              <div class="tab-pane fade show active" id="tabbox1" role="tabpanel" aria-labelledby="tabbox-tab1">
                <div id="setting-form">
                  <div class="card">
                    <div class="card-header">
                      <h4>Upload your site logo here</h4>
                    </div>
                    <form method="post" action="{{ route("admin.save_logo_setting") }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                      <div class="card-body">
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Upload Logo</label>
                          <div class="col-sm-6 col-md-9"><input type="file" class="form-control" name="logo_pic"></div>
                        </div>  
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Current Logo</label>
                          <div class="col-sm-6 col-md-9">
                            @if ($data2->val1 != "")
                                <img src="{{ asset($data2->val1) }}" class="" style="width: 250px;"/>
                            @else
                                <img src="{{ asset('uploads/styler.jpg') }}" class="" style="width: 250px;"/>
                            @endif
                          </div>
                        </div>    
                      </div>
                      <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox2" role="tabpanel" aria-labelledby="tabbox-tab2">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Basic Details</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_home_setting') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Contact No</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="contact_no" value="{{ $data1->val1 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Email</label>
                        <div class="col-sm-6 col-md-9"><input type="email" class="form-control" name="email_txt" value="{{ $data1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Address</label>
                        <div class="col-sm-6 col-md-9"><textarea class="form-control" name="address_txt">{{ $data1->val3 }}</textarea></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Header Center Text</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="center_txt" value="{{ $data1->val4 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox3" role="tabpanel" aria-labelledby="tabbox-tab3">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit 4 Information Box Details</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_infobox') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Icon 1</label>
                            <div class="col-sm-6 col-md-9"><button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info" role="iconpicker" name="icon_1" data-icon="{{ $info1->val2 }}"></button></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Title 1</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="title_1" value="{{ $info1->val3 }}" required=""></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Sub-title 1</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sub_title_1" value="{{ $info1->val4 }}" required=""></div>
                        </div>
                        <hr>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Icon 2</label>
                            <div class="col-sm-6 col-md-9"><button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info" role="iconpicker" name="icon_2" data-icon="{{ $info2->val2 }}"></button></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Title 2</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="title_2" value="{{ $info2->val3 }}" required=""></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Sub-title 2</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sub_title_2" value="{{ $info2->val4 }}" required=""></div>
                        </div>
                        <hr>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Icon 3</label>
                            <div class="col-sm-6 col-md-9"><button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info" role="iconpicker" name="icon_3" data-icon="{{ $info3->val2 }}"></button></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Title 3</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="title_3" value="{{ $info3->val3 }}" required=""></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Sub-title 3</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sub_title_3" value="{{ $info3->val4 }}" required=""></div>
                        </div>
                        <hr>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Icon 4</label>
                            <div class="col-sm-6 col-md-9"><button class="btn btn-primary" data-selected-class="btn-danger" data-unselected-class="btn-info" role="iconpicker" name="icon_4" data-icon="{{ $info4->val2 }}"></button></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Title 4</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="title_4" value="{{ $info4->val3 }}" required=""></div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-control-label col-sm-3 text-md-right">Sub-title 4</label>
                            <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sub_title_4" value="{{ $info4->val4 }}" required=""></div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox4" role="tabpanel" aria-labelledby="tabbox-tab4">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Homepage Product & Category Details</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_home_setting2') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Featured collection section Category List</label>
                        @php $procat = explode(',', $home_s2->val1); @endphp
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control select2" name="procat[]" multiple="">
                            <option value="0">select</option>
                            @foreach ($cate as $Category)
                            <option value="{{$Category->id}}" @if(in_array($Category->id, $procat)) selected @endif>{{ $Category->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">How many product show in Featured collection at home page</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="featu_pro_count" value="{{ $home_s2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">How many product show in New Arrivals at home page</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="newar_pro_count" value="{{ $home_s2->val3 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">How many product show in category and shop page</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="shop_product_count" value="{{ $home_s2->val4 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox5" role="tabpanel" aria-labelledby="tabbox-tab5">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Single Product & Shipping Details</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_product_setting') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Free Shipping Amount ({{ $currency }})</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="ship_amt" value="{{ $data11->val1 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">International Shipping Charge ({{ $currency }})</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="inte_chrge" value="{{ $data11->val4 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Domestic Shipping Charges ({{ $currency }})</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="dos_chrge" value="{{ $data11->val3 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Store Origin Country</label>
                          <div class="col-sm-6 col-md-9">
                            <select class="form-control select2" name="ship_count">
                              @foreach ($country as $coun)
                                <option value="{{ $coun->id }}" {{ $coun->id == $data11->val5 ? 'selected' : ''}}>{{ $coun->name }}</option>
                              @endforeach
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Estimate delivery days</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="ship_day" value="{{ $data11->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Currency Name</label>
                          <div class="col-sm-6 col-md-9">
                            <input type="text" class="form-control" name="cur_nm" value="{{ $data22->val3 }}" required="">
                            <div class="form-text text-muted">This details work in payment method also</div>
                          </div>
                      </div>    
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Currency Symbol</label>
                          <div class="col-sm-6 col-md-9">
                            <input type="text" class="form-control" name="cur_sym" value="{{ $data22->val4 }}" required="">
                            <div class="form-text text-muted">This details show in entire site</div>
                          </div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Returns policy text on prodct page</label>
                          <div class="col-sm-6 col-md-9"><textarea class="form-control" name="return_txt">{{ $data22->val1 }}</textarea></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Shipping policy text on prodct page</label>
                          <div class="col-sm-6 col-md-9"><textarea class="form-control" name="ship_txt">{{ $data22->val2 }}</textarea></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox6" role="tabpanel" aria-labelledby="tabbox-tab6">
                <div class="card">
                  <div class="card-header">
                    <h4>Paypal Settings</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_paypal') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="paypal_sts">
                            <option value="0" {{ $pay1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $pay1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Payment Mode</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="paypal_mod">
                            <option value="sandbox" {{ $pay1->val1 == "sandbox" ? 'selected' : ''}}>sandbox</option>
                            <option value="live" {{ $pay1->val1 == "live" ? 'selected' : ''}}>live</option>
                          </select></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Sandbox Client ID</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_id" value="{{ $pay1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Sandbox Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_sec" value="{{ $pay1->val3 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Sandbox App ID</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_app" value="{{ $pay1->val4 }}" required=""></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Live Client ID</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_id" value="{{ $pay2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Live Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_sec" value="{{ $pay2->val3 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Live App ID</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_app" value="{{ $pay2->val4 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox7" role="tabpanel" aria-labelledby="tabbox-tab7">
                <div class="card">
                  <div class="card-header">
                    <h4>Stripe Settings</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_stripe') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="stripe_sts">
                            <option value="0" {{ $stri1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $stri1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Payment Mode</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="stripe_mod">
                            <option value="sandbox" {{ $stri1->val1 == "sandbox" ? 'selected' : ''}}>sandbox</option>
                            <option value="live" {{ $stri1->val1 == "live" ? 'selected' : ''}}>live</option>
                          </select></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Key</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_key" value="{{ $stri1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_sec" value="{{ $stri1->val3 }}" required=""></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Live Client Key</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_key" value="{{ $stri2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Live Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_sec" value="{{ $stri2->val3 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox8" role="tabpanel" aria-labelledby="tabbox-tab8">
                <div class="card">
                  <div class="card-header">
                    <h4>Razorpay Settings</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_razorpay') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="razor_sts">
                            <option value="0" {{ $razor1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $razor1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Payment Mode</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="razor_mod">
                            <option value="sandbox" {{ $razor1->val1 == "sandbox" ? 'selected' : ''}}>sandbox</option>
                            <option value="live" {{ $razor1->val1 == "live" ? 'selected' : ''}}>live</option>
                          </select></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Key</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_key" value="{{ $razor1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_sec" value="{{ $razor1->val3 }}" required=""></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Live Client Key</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_key" value="{{ $razor2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Live Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_sec" value="{{ $razor2->val3 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox8" role="tabpanel" aria-labelledby="tabbox-tab8">
                <div class="card">
                  <div class="card-header">
                    <h4>Razorpay Settings</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_razorpay') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="razor_sts">
                            <option value="0" {{ $razor1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $razor1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Payment Mode</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="razor_mod">
                            <option value="sandbox" {{ $razor1->val1 == "sandbox" ? 'selected' : ''}}>sandbox</option>
                            <option value="live" {{ $razor1->val1 == "live" ? 'selected' : ''}}>live</option>
                          </select></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Key</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_key" value="{{ $razor1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Test Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="sand_sec" value="{{ $razor1->val3 }}" required=""></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Live Client Key</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_key" value="{{ $razor2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Live Client Secret</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="live_sec" value="{{ $razor2->val3 }}" required=""></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox9" role="tabpanel" aria-labelledby="tabbox-tab9">
                <div class="card">
                  <div class="card-header">
                    <h4>Payment Note for user & COD</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_paycod') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Payment Note (for all user & all payment type)</label>
                        <div class="col-sm-6 col-md-9"><textarea class="form-control" name="pay_note">{{ $paycod1->val1 }}</textarea></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Cash On Delivery <br>(COD) Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="cod_sts">
                            <option value="0" {{ $paycod1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $paycod1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">COD Limit ({{ $currency }})</label>
                        <div class="col-sm-6 col-md-9">
                          <input type="text" class="form-control" name="cod_limit" value="{{ $paycod1->val2 }}" required="">
                          <div class="form-text text-muted">Cart value is bigger then COD limit then after user can see COD payment option</div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox10" role="tabpanel" aria-labelledby="tabbox-tab10">
                <div class="card">
                  <div class="card-header">
                    <h4>Mail Settings</h4>
                  </div>
                  <form method="post" action="{{ route('admin.save_mailset') }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                      {{-- <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Cash On Delivery <br>(COD) Status</label>
                        <div class="col-sm-6 col-md-9">
                          <select class="form-control" name="cod_sts">
                            <option value="0" {{ $paycod1->val5 == 0 ? 'selected' : ''}}>Disabled</option>
                            <option value="1" {{ $paycod1->val5 == 1 ? 'selected' : ''}}>Enable</option>
                          </select></div>
                      </div> --}}
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail Host</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_host" value="{{ $mailset1->val1 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail Port</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_port" value="{{ $mailset1->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail Username</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_user" value="{{ $mailset1->val3 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail Password</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_pass" value="{{ $mailset1->val4 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail Encryption</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_encr" value="{{ $mailset2->val1 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail From Email Name</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_name" value="{{ $mailset2->val2 }}" required=""></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Mail From Email Address</label>
                        <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="mail_addr" value="{{ $mailset2->val3 }}" required=""></div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Admin Email ID</label>
                        <div class="col-sm-6 col-md-9">
                          <input type="text" class="form-control" name="adminemail" value="{{ $mailset2->val4 }}" required="">
                          <div class="form-text text-muted">Received every mail like new register, order, order status, etc</div>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group row align-items-center">
                        <label class="form-control-label col-sm-3 text-md-right">Send Test Mail</label>
                        <div class="col-sm-6 col-md-7"><input type="text" class="form-control" id="mail_test"></div>
                        <div class="col-sm-6 col-md-2"><button type="button" class="sendmail btn btn-primary">Send Mail</button></div>
                      </div>
                      <div class="form-group row align-items-center">
                        <div class="col-sm-12 col-md-12 mailresponse"></div>
                      </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-md-right">
                      <button class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="tabbox11" role="tabpanel" aria-labelledby="tabbox-tab11">
                <div id="setting-form">
                  <div class="card">
                    <div class="card-header">
                      <h4>Upload Banner and set link for offer, discount, special product, etc</h4>
                    </div>
                    <form method="post" action="{{ route("admin.save_popup") }}" class="needs-validation" novalidate="" enctype="multipart/form-data">
                    @csrf
                      <div class="card-body">
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Upload Banner</label>
                          <div class="col-sm-6 col-md-9"><input type="file" class="form-control" name="pop_img"></div>
                        </div>  
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Current Banner</label>
                          <div class="col-sm-6 col-md-9">
                            @if ($popup->val1 != "")
                                <img src="{{ asset($popup->val1) }}" class="" style="width: 250px;"/>
                            @endif
                          </div>
                        </div>
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">Set link for Image</label>
                          <div class="col-sm-6 col-md-9"><input type="text" class="form-control" name="pop_link" value="{{ $popup->val2 }}" required=""></div>
                        </div> 
                        <div class="form-group row align-items-center">
                          <label class="form-control-label col-sm-3 text-md-right">How manytimes popup show?</label>
                          <div class="col-sm-6 col-md-9"><input type="number" min="0" max="9" class="form-control" name="pop_time" value="{{ $popup->val3 }}" required=""><div class="form-text text-muted">This will work for entire site, not for any page or product. Image size = 700x400</div></div>
                        </div>     
                      </div>
                      <div class="card-footer bg-whitesmoke text-md-right">
                        <button class="btn btn-primary">Update</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
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
    .select2-container {
      width: 100% !important;
    }
  </style>

@endsection

@push('scripts')

  <link rel="stylesheet" href="http://ecommerce.test/backend/assets/css/bootstrap-iconpicker.min.css">
  <script src="http://ecommerce.test/backend/assets/js/bootstrap-iconpicker.bundle.min.js"></script>

  <link rel="stylesheet" href="{{ asset('bassets/modules/select2/dist/css/select2.min.css') }} ">
  <script src="{{ asset('bassets/modules/select2/dist/js/select2.full.min.js') }} "></script>

  <script>
    $(document).ready(function(){
      $('.sendmail').on('click', function(){
        var email = $("#mail_test").val();
        $.ajax({
            method: "POST",
            url: "{{ route('admin.sendtestmail') }}",
            data: { _token: "{{ csrf_token() }}", email: email },
            success: function(data){
                if(data.status == "success"){
                    flasher.success(data.message);
                } else {
                    flasher.warning(data.message);
                }
            },
            error: function(data){ console.log(data); }
        });
      });
    });
  </script>
  .sendmail

@endpush


