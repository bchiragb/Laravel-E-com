@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div class="slideshow slideshow-wrapper pb-section sliderFull">
        <div class="home-slideshow">
            @foreach ($slider as $slide)
                <div class="slide">
                    <div class="blur-up lazyload bg-size">
                        <img class="blur-up lazyload bg-img" data-src="{{ asset($slide->val1) }}" src="{{ asset($slide->val1) }}" alt="{{ $slide->val2 }}" title="{{ $slide->val2 }}" />
                        <div class="slideshow__text-wrap slideshow__overlay classic bottom">
                            <div class="slideshow__text-content bottom">
                                <div class="wrap-caption center">
                                    <h2 class="h1 mega-title slideshow__title">{{ $slide->val2 }}</h2>
                                    <span class="mega-subtitle slideshow__subtitle">{{ $slide->val3 }}</span>
                                    @if(!empty($slide->val4) && $slide->val4 != "#")
                                        <a href="{{ $slide->val4 }}"><span class="btn">Shop now</span></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="tab-slider-product section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="section-header text-center">
                        <h2 class="h2">Featured collection</h2>
                        <p>Timeless Fashion for the Modern Shopper</p>
                    </div>
                    <div class="tabs-listing">
                        <ul class="tabs clearfix">
                            @foreach ($catdata as $cat)
                                @if($loop->iteration == 1)
                                    <li class="active" rel="tab{{ $loop->iteration }}">{{ $cat['name'] }}</li>
                                @else    
                                    <li rel="tab{{ $loop->iteration }}">{{ $cat['name'] }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab_container">
                            @foreach ($promas as $key => $pro)
                            <div id="tab{{ $loop->iteration }}" class="tab_content grid-products  {{ $key }}">
                                <div class="productSlider">
                                    @foreach ($pro as $key => $product)
                                        {!! $product !!}
                                    @endforeach
                                </div>
                            </div> 
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>    
        </div>
    </div>
    <div class="collection-box section">
        <div class="container-fluid">
            <div class="collection-grid">
            @foreach ($pro_cat as $cat)
                <div class="collection-grid-item">
                    <a href="collection-page.html" class="collection-grid-item__link">
                        <img data-src="{{ asset($cat->img) }}" src="{{ asset($cat->img) }}" alt="{{ $cat->name }}" class="blur-up lazyload"/>
                        <div class="collection-grid-item__title-wrapper">
                            <h3 class="collection-grid-item__title btn btn--secondary no-border">{{ $cat->name }}</h3>
                        </div>
                    </a>
                </div>
            @endforeach
            </div>
        </div>
    </div>
    <div class="section logo-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="logo-bar">
                        @foreach ($brands as $brand)
                            <div class="logo-bar__item">
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" title="{{ $brand->name }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-rows section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="section-header text-center">
                        <h2 class="h2">New Arrivals</h2>
                        <p>Be the First to Explore Our Newest Collection</p>
                    </div>
                </div>
            </div>
            <div class="grid-products">
                <div class="row">
                    @foreach ($newproductx as $pro)
                        {!! $pro !!}
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
                        <a href="{{ URL::to('/shop') }}" class="btn">View all</a>
                    </div>
                </div>
            </div>
       </div>
    </div>	

    <div class="store-feature section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="display-table store-info">
                        @foreach ($infobox as $infbox)
                            <li class="display-table-cell">
                                <i class="iconx {{ $infbox->val2 }}"></i>
                                <h5>{{ $infbox->val3 }}</h5>
                                <span class="sub-text">{{ $infbox->val4 }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="ProductSection-product-template" class="product-template__container prstyle1">
                    <div class="product-single">
                        <a href="javascript:void()" data-dismiss="modal" class="model-close-btn pull-right" title="close"><span class="icon icon anm anm-times-l"></span></a>
                        <div class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="modal fade" id="variantmodal" tabindex="-1" aria-labelledby="variantmodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="myModalx" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Modal Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
          This is the body of the modal.
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        
      </div>
    </div>
  </div> --}}

    <style>
    #variantmodal .product-action {
        padding: 0px;
        margin-top: 30px;
        margin-bottom: 0px;
    }
    #variantmodal .product-single__meta {
        margin-bottom: 0px;
    }
    </style>
  
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('bassets/modules/fontawesome/css/all.min.css') }}">

<script>
    $(document).ready(function(){
        $('.quick-view').click(function(){
            //$('#myModal').modal('show');
            var idx = $(this).attr('data-val');
            $.ajax({
                type: "GET",
                url: "{{ route('getproduct') }}",
                data: { _token: "{{ csrf_token() }}", pidx:idx },
                success: function(res) {
                    $('.quickview-popup .modal-body').html('');
                    $('.quickview-popup .modal-body').append(res);
                    $('.quickview-popup').modal('show');
                },
                error:function(request, status, error) {
                    console.log("ajax call went wrong:" + request.responseText);
                }
            });
        });
        //
        $('.add-to-wishlist').on('click', function(e){
            var $this = $(this);
            var idx = $this.attr('data-val');
            $.ajax({
                method: "POST",
                url: "{{ route('wishlist.store') }}",
                data: { _token: "{{ csrf_token() }}", idx: idx},
                success: function(data){ 
                    if(data.status == "success"){ 
                        flasher.success(data.message); 
                        $this.find('i').removeClass();
                        $this.find('i').addClass('icon anm anm-heart redwish');
                    } else {
                        flasher.info(data.message);
                        $this.find('i').removeClass();
                        $this.find('i').addClass('icon anm anm-heart redwish');
                    } 
                },
                error: function(data){ console.log(data); }
            });
        });
        //
        $(document).on('click', '.addtocart', function(){
            var idx = $(this).val();
            //var idx = $(this).val();
            if(idx == 0){ alert('Please select your product variation'); } else {
                var pidx = $(this).attr('data-type');
                var qtyx = 1;
                $.ajax({
                    method: "POST",
                    url: "{{ route('addtocart') }}",
                    //data: { _token: "{{ csrf_token() }}", skuidx:idx, pidx:pidx, quantity:qtyx },
                    data: { _token: "{{ csrf_token() }}", pidx:idx },
                    //data: $('#addtocartform').serialize(),
                    success: function(data){
                        //$('#variantmodal').hide(); //close popup
                        $('#variantmodal').modal('hide'); // Close the modal
                        if(data.status == "success"){
                            flasher.success(data.message);
                            //$("#CartCount").html(data.qty);
                            //
                            $(".mini-products-list .nopro").hide();
                            $("#header-cart").html('');
                            $("#header-cart").append(data.html);
                            //$(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                            $('.quickview-popup').modal('hide');
                            //cart counter not working
                            $('#CartCount').html(data.qty);
                        } else {
                            flasher.error("Error");
                        }                                   
                    },
                    error: function(data){ console.log(data); }
                });
            }
        });
        //
        $(document).on('click', '.variantonly', function(){
            var idx = $(this).attr('data-val');
            if(idx == 0){ alert('Please select your product variation'); } else {
                $('#variantmodal').modal('hide');
                $.ajax({
                    method: "GET",
                    url: "{{ route('onlyvariant') }}",
                    data: { _token: "{{ csrf_token() }}", pidx:idx },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $("#CartCount").html(data.qty);
                            $('#variantmodal').modal('hide');
                            //
                            $(".mini-products-list .nopro").hide();
                            $(".mini-products-list").html('');
                            $(".mini-products-list").append(data.html);
                            $(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                        }  
                        $('#variantmodal .modal-body').html('');
                        $('#variantmodal .modal-body').append(data);
                        $('#variantmodal').modal('show');                                 
                    },
                    error: function(data){ console.log(data); }
                });
            }
        });
    });
  </script>
@endpush


