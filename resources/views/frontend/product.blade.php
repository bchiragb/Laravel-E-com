@extends('frontend.layout.master')

@section('body_content')

@php
    //echo "<br>"; echo $product->id.'=';
    //pro_img pro_vari
    $productstock = 0;
    $product_img = [];
    $product_img[] = $product->img1;
    $product_img[] = $product->img2;
    foreach ($pro_img as $pimg){ $product_img[] = $pimg->image; }
    //
    $taglistx = ""; $tagnmx = array();
    foreach (explode(',', $product->tag) as $tag) {
        if($tag == 1) { $tagnmx[] = "NEW";
            $taglistx .= '<span class="lbl pr-label1">NEW</span>';
        } elseif ($tag == 2) { $tagnmx[] = "HOT";
            $taglistx .= '<span class="lbl pr-label2">HOT</span>';
        } elseif ($tag == 3) { $tagnmx[] = "POPULAR";
            $taglistx .= '<span class="lbl pr-label3">POPULAR</span>';                                            
        }   
    }
    $tagnm = implode(', ', $tagnmx);
    $remainval = calcfreeship();
    //
    if($product->ptype == 1) { 
        $proclass = "simpleproduct"; $protype = "simple"; 
        $sku_pro = ucfirst($product->sku);
        $stock_pro = $product->stock;
        $productstock = $product->stock;
        $rprice_pro = $product->rprice;
        $sprice_pro = $product->sprice;
        //
        if($product->sprice != '') {
            $saveamtper = saveamt($product->rprice, $product->sprice);
        }  else { $saveamtper[0] = 0; $saveamtper[1] = 0; }
        //
        $variant_prox = '-';
        //$productid = $product->id.strtotime('now');
        $productid = encode_proid_typ($product->id, 1);
    } else { 
        $productstock = 1;
        $proclass = "variantproduct"; $protype = "variant"; 
        $sku_pro = ucfirst(getdatax($pro_vari, 'sku'));
        $stock_pro = getlowstock($pro_vari);
        $pri_pro = getprices($pro_vari);
        if($pri_pro[0] == $pri_pro[1]) { //same price of all variant
            $rprice_pro = $pri_pro[0];     
        } else {
            $rprice_pro = $pri_pro[0].'-'.$currency.$pri_pro[1]; 
        }
        
        if($pri_pro[2] == 0) {
            $sprice_pro = 0; 
        } else {
            if($pri_pro[2] == $pri_pro[3]) {
                $sprice_pro = $pri_pro[2];
            } else {
                $sprice_pro = $pri_pro[2].'-'.$currency.$pri_pro[3];
            }
        }
        
        //
        if($pri_pro[3] != '' && $pri_pro[3] != 0) {
            $saveamtper = saveamt($pri_pro[1], $pri_pro[3]);
        } else { $saveamtper[0] = 0; $saveamtper[1] = 0; }
        //
        $variant_pro = getvariantcol($product->id);
        $variant_prox = str_replace('_', ' ', $variant_pro[0]); 
        //$productid = $product->id.strtotime('now');
        $productid = encode_proid_typ($product->id, 2);
        //echo '<script>'; echo 'var masfiled = ' . json_encode($variant_pro[1]) . '; '; echo '</script>';
        //echo '<script>'; echo 'var masfiled = ' . {{ $coljsonVariant }} . ';'; echo '</script>';
        echo '<script>'; echo "
            const masfiled = $coljsonVariant;  
            const variants = $jsonVariants;
        "; echo '</script>';

        
    }
    //
    $salend = $product->stdt != $product->eddt ? $product->eddt : '';
    $currentTimestamp = strtotime("now"); // Current date in timestamp
    $recordTimestamp = strtotime($product->eddt . '00:00:00');
    if ($currentTimestamp > $recordTimestamp) {
        $enddt = 'No Sale';
    } elseif ($currentTimestamp < $recordTimestamp) {
        $enddt = $product->eddt;
    } 
    //
    $estdate = estdate($pro_sett1->val2);
    //
    $urlu = route('home').'/product/'.$product->catnm->slug;
@endphp

{{-- <input type="hidden" class="pro_data_json" data-laravel="{{ $getcolsx[3] }}"/> --}}

{{-- @if(isMobile())
    <p>You are on a mobile device.</p>
@else
    <p>You are on a desktop device.</p>
@endif --}}

<div id="page-content" class="{{ $proclass }} pro_{{ $product->id }}">
    <div id="MainContent" class="main-content" role="main">
        <div class="bredcrumbWrap">
            <div class="container breadcrumbs">
                <a href="{{ route('home') }}" title="Back to the home page">Home</a><span aria-hidden="true">›</span>
                <a href="{{ route('home').'/category/'.$product->catnm->slug }}" title="Back to the home page">{{ $product->catnm->name }}</a><span aria-hidden="true">›</span>
                <span>{{ $product->title }}</span>
            </div>
        </div>
        
        <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
            <div class="product-single">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="product-details-img">
                            <div class="product-thumb">
                                <div id="gallery" class="product-dec-slider-2 product-tab-left">
                                    @foreach ($product_img as $pimg)
                                        <a data-image="{{ asset($pimg) }}" data-zoom-image="{{ asset($pimg) }}" class="slick-slide slick-cloned" data-slick-index="" aria-hidden="true" tabindex="-1">
                                            <img class="blur-up lazyload" data-src="{{ asset($pimg) }}" src="{{ asset($pimg) }}" alt="" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="zoompro-wrap product-zoom-right pl-20">
                                <div class="zoompro-span">
                                    <img class="blur-up lazyload zoompro" data-zoom-image="{{ asset($product->img1) }}" alt="" src="{{ asset($product->img1) }}" />
                                </div>
                                <div class="product-labels">
                                    @if ($product->sprice != '')
                                        <span class="lbl on-sale">SALE</span>
                                    @endif                                
                                    {!! $taglistx !!}
                                </div>
                                <div class="product-buttons">
                                    @php if($product->video != '') { echo '<a href="'.$product->video.'" class="btn popup-video" title="View Video"><i class="icon anm anm-play-r" aria-hidden="true"></i></a>'; } @endphp
                                    <a href="#" class="btn prlightbox" title="Zoom"><i class="icon anm anm-expand-l-arrows" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="lightboximages">
                                @foreach ($product_img as $pimg)
                                   <a href="{{ asset($pimg) }}" data-size="1462x2048"></a>
                                @endforeach
                            </div>
                        </div>
                        <!-- <div class="product-details-img product-single__photos bottom">
                            <div class="zoompro-wrap product-zoom-right pl-20">
                                <div class="zoompro-span">
                                    <img class="blur-up lazyload zoompro" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-1.jpg') }}" alt="" src="{{ asset('fassets/images/product-detail-page/cape-dress-1.jpg') }}" />               
                                </div>
                                <div class="product-labels"><span class="lbl on-sale">Sale</span><span class="lbl pr-label1">new</span></div>
                                <div class="product-buttons">
                                    <a href="https://www.youtube.com/watch?v=93A2jOW5Mog" class="btn popup-video" title="View Video"><i class="icon anm anm-play-r" aria-hidden="true"></i></a>
                                    <a href="#" class="btn prlightbox" title="Zoom"><i class="icon anm anm-expand-l-arrows" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="product-thumb product-thumb-1">
                                <div id="gallery" class="product-dec-slider-1 product-tab-left">
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-1.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-1.jpg') }}" class="slick-slide slick-cloned" data-slick-index="-4" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-1.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-2.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-2.jpg') }}" class="slick-slide slick-cloned" data-slick-index="-3" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-2.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-3.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-3.jpg') }}" class="slick-slide slick-cloned" data-slick-index="-2" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-3.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-4.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-4.jpg') }}" class="slick-slide slick-cloned" data-slick-index="-1" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-4.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-5.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-5.jpg') }}" class="slick-slide slick-cloned" data-slick-index="0" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-5.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-6.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-6.jpg') }}" class="slick-slide slick-cloned" data-slick-index="1" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-6.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-7.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-7.jpg') }}" class="slick-slide slick-cloned" data-slick-index="2" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-7.jpg') }}" alt="" />
                                    </a>
                                    <a data-image="{{ asset('fassets/images/product-detail-page/cape-dress-8.jpg') }}" data-zoom-image="{{ asset('fassets/images/product-detail-page/cape-dress-8.jpg') }}" class="slick-slide slick-cloned" data-slick-index="3" aria-hidden="true" tabindex="-1">
                                        <img class="blur-up lazyload" src="{{ asset('fassets/images/product-detail-page/cape-dress-8.jpg') }}" alt="" />
                                    </a>
                                </div>
                            </div>
                            <div class="lightboximages">
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big1.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big2.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big3.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible7-big.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big4.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big5.jpg') }}" data-size="1462x2048"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big6.jpg') }}" data-size="731x1024"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big7.jpg') }}" data-size="731x1024"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big8.jpg') }}" data-size="731x1024"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big9.jpg') }}" data-size="731x1024"></a>
                                <a href="{{ asset('fassets/images/product-detail-page/camelia-reversible-big10.jpg') }}" data-size="731x1024"></a>
                            </div>
                        </div> -->
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="product-single__meta">
                                <h1 class="product-single__title">{{ $product->title }}</h1>
                                
                                    {{-- <div class="product-stock sales-point"> <span class="icon icon--inventory"></span><span class="instock stockm">In stock, ready to ship</span></div> --}}
                                    
                                    @php $off = ""; $on = ""; if($productstock == 0) { $off = "hideme"; } else { $on = "hideme"; } @endphp
                                    <div class="row box2">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="product-sku"> SKU: <span class="variant-sku txtupr">{{ $sku_pro }}</span></div>
                                        </div>
                                    </div>
                                    <div class="row box1">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-9 w-100 d-flex justify-content-start align-items-center">
                                            <div class="product-stock sales-point inventory_icon_no {{ $on }}"> <span class="icon"></span>Sorry, this product is unavailable</div>
                                            <div class="product-stock sales-point inventory_icon {{ $off }}"> <span class="icon"></span>In stock, ready to ship</div>                                                
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-3 d-flex justify-content-end align-items-center">
                                            <div class="product-review">
                                                <a class="reviewLink" href="#tab2"><span class="spr-badge-caption">{{ $num_ratings }} reviews (<i class="font-13 fa fa-star"></i> {{ $average_rating }}/5)</span></a>
                                            </div>
                                        </div>
                                    </div>    
                                </div>

                                <p class="product-single__price product-single__price-product-template">
                                    <span class="visually-hidden">Regular price</span>
                                    @if($sprice_pro != 0)
                                    <s id="ComparePrice-product-template"><span class="money">{{ $currency }}{{ $rprice_pro }}</span></s>
                                    <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                        <span id="ProductPrice-product-template"><span class="money">{{ $currency }}{{ $sprice_pro }}</span></span>
                                    </span>
                                    @else
                                    <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                        <span id="ProductPrice-product-template"><span class="money">{{ $currency }}{{ $rprice_pro }}</span></span>
                                    </span>
                                    @endif

                                    @if($saveamtper[0] != 0)
                                    <span class="discount-badge"> <span class="devider">|</span>&nbsp;
                                        <span>You Save</span>
                                        <span id="SaveAmount-product-template" class="product-single__save-amount">
                                        <span class="money">{{ $currency }}{{ $saveamtper[0] }}</span>
                                        </span>
                                        <span class="off">(<span>{{ $saveamtper[1] }}</span>% OFF)</span>
                                        (Inclusive of all Taxes)
                                    </span>  
                                    @endif
                                </p>
                            <div class="product-single__description rte">
                                {{ $product->desc }}
                            </div>
                            <form method="post" action="#" id="addtocartform" accept-charset="UTF-8" class="product-form product-form-product-template hidedropdown" enctype="multipart/form-data">
                                @csrf
                                @if($product->ptype == 2)
                                    <input type="hidden" value="0" id="pidx" name="pidx"/>
                                    <div class="product-container"></div>
                                    <div class="swatchxx clearfix spx_pro_box" data-option-index="0" >
                                        <div class="product-form__item ">
                                            <div class="product-single__price">SKU: <span class="sku_val product-price__price product-price__sale">-</span></div>
                                            <div class="product-single__price">Price: <span class="price_val product-price__price product-price__sale">{{ $currency }} 0</span></div>
                                        </div>
                                        <div class="swatch-element available"><button class="swatchLbl medium rectangle clearx clear-button" id="clear-selection" title="clear" type="button">clear</button></div>
                                    </div>
                                @else
                                    <input type="hidden" value="{{ $productid }}" id="pidx" name="pidx"/>
                                @endif
                                <p class="infolinks"><a href="#sizechart" class="sizelink btn"> Size Guide</a> <a href="#productInquiry" class="emaillink btn"> Ask a Question</a></p>
                                <div id="discount-container">
                                    @foreach ($coupon as $cou)
                                        <div class="offer-container">
                                            <div class="discount-title">{{ $cou->desc }}</div><span class="code">{{ $cou->code }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="product-action clearfix">
                                    <div class="product-form__item--quantity">
                                        <div class="wrapQtyBtn">
                                            <div class="qtyField">
                                                <a class="qtyBtn minus" href="javascript:void(0);"><i class="fa anm anm-minus-r" aria-hidden="true"></i></a>
                                                <input type="text" id="Quantity" name="qty" value="1" class="product-form__input qty" min="1" max="{{ $stock_pro }}">
                                                <a class="qtyBtn plus" href="javascript:void(0);"><i class="fa anm anm-plus-r" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>        
                                   
                                    @php $off = ""; if($productstock == 0) { $off = "disabled"; } @endphp
                                    <div class="product-form__item--submit">
                                        <button type="button" class="btn product-form__cart-submit addtocart" {{ $off }} value="{{ $productid }}"><span>Add to cart</span></button>
                                    </div>
                                    <div class="shopify-payment-button" data-shopify="payment-button">
                                        <button type="button" class="shopify-payment-button__button shopify-payment-button__button--unbranded" {{ $off }}  id="buynow">Buy it now</button>
                                    </div>
                                </div>
                                <!-- End Product Action -->
                            </form>
                            <div class="display-table shareRow">
                                    <div class="display-table-cell medium-up--one-third">
                                        <div class="wishlist-btn">
                                            <a class="wishlist add-to-wishlist" href="javascript:void(0)"  data-val="{{ $productid }}" title="Add to Wishlist"><i class="icon anm anm-heart-l" aria-hidden="true"></i> <span>Add to Wishlist</span></a>
                                        </div>
                                    </div>
                                    <div class="display-table-cell text-right">
                                        <div class="social-sharing">
                                            <a target="_blank" href="https://www.facebook.com/sharer.php?u={{ $urlu }}" class="btn btn--small btn--secondary btn--share share-facebook" title="Share on Facebook">
                                                <i class="fab fa-facebook-square" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Share</span>
                                            </a>
                                            <a target="_blank" href="https://twitter.com/intent/tweet?url={{ $urlu }}" class="btn btn--small btn--secondary btn--share share-twitter" title="Tweet on Twitter">
                                                <i class="fab fa-twitter" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Tweet</span>
                                            </a>
                                            <a target="_blank" href="https://www.pinterest.com/pin/find/?url={{ $urlu }}" class="btn btn--small btn--secondary btn--share share-pinterest" title="Pin on Pinterest">
                                                <i class="fab fa-pinterest" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Pin it</span>
                                            </a>
                                            <a href="https://api.whatsapp.com/send?text={{ $urlu }}" class="btn btn--small btn--secondary btn--share share-whatsapp" title="Share by Whatsapp" target="_blank">
                                                <i class="fab fa-whatsapp-square" aria-hidden="true"></i> <span class="share-title" aria-hidden="true">Whatsapp</span>
                                            </a>
                                         </div>
                                    </div>
                                </div>
                            <p id="freeShipMsg" class="freeShipMsg"><i class="fa fa-truck" aria-hidden="true"></i> GETTING CLOSER! ONLY <b class="freeShip"><span class="money">{{ $currency }}{{ $remainval }}</span></b> AWAY FROM <b>FREE SHIPPING!</b></p>
                            <p class="shippingMsg"><i class="fa fa-clock-o" aria-hidden="true"></i> ESTIMATED DELIVERY  DATE <b id="fromDate">{{ $estdate }}</b>*</p>
                            <div class="prInfoRow infoboxx">
                                <div class="product-sku"><i class="fa fa-globe" aria-hidden="true"></i> Shipping Worldwide</div>
                                <div class="product-sku"><i class="fa fa-award" aria-hidden="true"></i> Assured Quality</div>
                                <div class="product-sku"><i class="fa fa-shield-alt" aria-hidden="true"></i> 100% Payment Protection</div>
                            </div>
                        </div>
                </div>
            </div>
            
            <div class="tabs-listing">
                <ul class="product-tabs">
                    <li rel="tab1"><a class="tablink">Product Details</a></li>
                    <li rel="tab2"><a class="tablink">Product Reviews</a></li>
                    <li rel="tab3"><a class="tablink">Shipping &amp; Returns</a></li>
                    <li rel="tab4"><a class="tablink">Additional Info / Care Guide / Instruction</a></li>
                </ul>
                <div class="tab-container">
                    <div id="tab1" class="tab-content">
                        <div class="product-description rte">
                            <ul>
                                <li><b>SKU:</b> <span class="txtupr">{{ $sku_pro }}</span></li>
                                <li><b>Brand:</b> {{ $product->brandnm->name }}</li>
                                <li><b>Category:</b> {{ $product->catnm->name }}</li>
                                @if($tagnm != "") <li><b>Tag:</b> {{ $tagnm }}</li> @endif
                                <li><b>Stock:</b> {{ $stock_pro }}</li>
                                <li><b>Type:</b> {{ $protype }}</li>
                                @if($variant_prox != "-") <li><b>Variant:</b> <span class="txtcap">{{ $variant_prox }}</span></li>@endif
                                @if($enddt != "No Sale")<li><b>Deal End Date:</b> {{ $enddt }}</li>@endif
                            </ul>
                        </div>
                    </div>
                    
                    <div id="tab2" class="tab-content">
                        <div id="shopify-product-reviews">
                            <div class="spr-container">
                                <div class="spr-content">
                                    <div class="spr-reviews">
                                        {!! $reviewhtml !!}
                                    </div>
                                    <div class="spr-form clearfix">                                    
                                        <form method="post" id="new-review-form" class="new-review-form">
                                            @csrf
                                            <h3 class="spr-form-title">Write a review</h3>
                                            <input type="hidden" value="{{ $productid }}" id="skuidxx" name="skuidxx"/>
                                            <div class="containerx">
                                                <div class="row">
                                                    <div class="col-1 col-sm-1 col-md-1 col-lg-1 stat_review">
                                                        <div class="spr-form-input spr-starrating">
                                                            <div class="rating"> <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label> <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label> <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label> <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label> <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label> </div>
                                                        </div>
                                                    </div>
                                                   <div class="col-5 col-sm-5 col-md-5 col-lg-5">
                                                        <input class="spr-form-input spr-form-input-text" type="text" name="review_title" value="" required placeholder="Give your review a title">
                                                    </div>
                                                    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                                                        <input class="spr-form-input spr-form-input-text" type="text" name="review_name" value="" required placeholder="Enter your name">
                                                    </div>
                                                    <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                                                        <input class="spr-form-input spr-form-input-email" type="email" name="review_email" value="" required placeholder="Enter your email">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="spr-form-input">
                                                            <textarea class="spr-form-input spr-form-input-textarea" name="review_txt" rows="2" required placeholder="Write your review here"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           <fieldset class="spr-form-actions">
                                                <input type="submit" class="spr-button spr-button-primary button button-primary btn btn-primary sub_review" value="Submit Review">
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                                <div class="spr-header clearfix">
                                    <div class="spr-summary">
                                        <span class="product-review">{{ $average_rating }}/5 <span class="spr-summary-actions-togglereviews">Based on {{ $num_ratings }} reviews</span></span>
                                        <span class="spr-summary-actions">
                                            <a href="javascript:;" class="spr-summary-actions-newreview btn">Write a review</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div id="tab3" class="tab-content">
                        @if($pro_sett2->val1 != '')
                            <h4>Returns Policy</h4>
                            <p>{{ $pro_sett2->val1 }}</p>
                        @endif
                        @if ($pro_sett2->val2 != '')
                            <h4>Shipping Policy</h4>
                            <p>{{ $pro_sett2->val2 }}</p>
                        @endif
                    </div>
                    <div id="tab4" class="tab-content">
                        {!! $product->info !!}
                    </div>
                </div>
            </div>
            <div class="related-product grid-products">
                <header class="section-header">
                    <h2 class="section-header__title text-center h2"><span>Related Products</span></h2>
                </header>
                <div class="productPageSlider">
                    @foreach($relatedproduct as $rel_product)
                        {!! $rel_product !!}
                    @endforeach
                </div>
            </div>
    </div>
</div>

<div class="hide">
    <div id="sizechart">
      <div style="padding-left: 30px;"><img src="{{ asset('fassets/images/size.jpg') }}" alt=""></div>
    </div>
</div>
<div class="modal fade" id="variantmodal" tabindex="-1" aria-labelledby="variantmodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<div class="hide">
    <div id="productInquiry">
        <div class="contact-form form-vertical">
            <div class="page-title">
                <h3>{{ $product->title }}</h3>
            </div>
            <form method="post" action="#" id="product_contact" class="contact-form">
                <div class="formFeilds">
                    @csrf
                    <input type="hidden" name="inq_product" value="{{ $productid }}">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="text" id="ContactFormName" name="inq_name" placeholder="Your Name *"  required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <input type="email" id="ContactFormEmail" name="inq_email" placeholder="Email *"  autocapitalize="off" required>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <input required type="tel" id="ContactFormPhone" name="inq_phone" pattern="[0-9\-]*" placeholder="contact *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <textarea required rows="10" id="ContactFormMessage" name="inq_body" placeholder="Message *" ></textarea>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="submit" class="btn" value="Send Message" id="submitBtn">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('fassets/js/vendor/photoswipe.min.js') }}"></script>
    <script src="{{ asset('fassets/js/vendor/photoswipe-ui-default.min.js') }}"></script>
    <script>
        $(function(){
            var $pswp = $('.pswp')[0],
                image = [],
                getItems = function() {
                    var items = [];
                    $('.lightboximages a').each(function() {
                        var $href   = $(this).attr('href'),
                            $size   = $(this).data('size').split('x'),
                            item = {
                                src : $href,
                                w: $size[0],
                                h: $size[1]
                            }
                            items.push(item);
                    });
                    return items;
                }
            var items = getItems();
            $.each(items, function(index, value) {
                image[index]     = new Image();
                image[index].src = value['src'];
            });
            $('.prlightbox').on('click', function (event) {
                event.preventDefault();
                var $index = $(".active-thumb").parent().attr('data-slick-index');
                $index++;
                $index = $index-1;
                var options = {
                    index: $index,
                    bgOpacity: 0.9,
                    showHideOpacity: true
                }
                var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                lightBox.init();
            });
            //
            $(document).ready(function(){
                //qty fix value
                $('#Quantity').on('input', function() {
                    var value = parseInt($(this).val(), 10);
                    var max = parseInt($(this).attr('max'), 10);
                    if(value > max) {
                        $(this).val(max); 
                    }
                });
                //qty fix value - When the "plus" button is clicked
                $('.qtyBtn.plus').on('click', function() {
                    var $quantityInput = $('#Quantity');
                    var currentValue = parseInt($quantityInput.val(), 10);
                    var maxValue = parseInt($quantityInput.attr('max'), 10);
                    if(currentValue < maxValue) {
                        $quantityInput.val(currentValue + 1);
                    } else {
                        $quantityInput.val(maxValue);
                    }
                });
                //add to cart
                $('#buynow').on('click', function(){
                    var idx = $('#pidx').val();
                    if(idx == 0){ alert('Please select your product variation'); } else {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('add_to_cart') }}",
                            data: $('#addtocartform').serialize(),
                            success: function(data){
                                if(data.status == "success"){
                                    flasher.success(data.message);
                                    setTimeout(function() {
                                        var url = "{{ route('checkout') }}"; 
                                        window.location.href = url;
                                    }, 1000);
                                } else {
                                    flasher.error("Error");
                                }                                
                            },
                            error: function(data){ console.log(data); }
                        });
                    }
                });
                //add to cart - single
                $('.addtocart').on('click', function(){
                    var idx = $('#pidx').val();
                    if(idx == 0){ alert('Please select your product variation'); } else {
                        $.ajax({
                            method: "POST",
                            url: "{{ route('addtocart') }}",
                            data: $('#addtocartform').serialize(),
                            success: function(data){
                                if(data.status == "success"){
                                    flasher.success(data.message);
                                    $("#CartCount").html(data.qty);
                                    //
                                    $(".mini-products-list .nopro").hide();
                                    $(".mini-products-list").html('');
                                    $(".mini-products-list").append(data.html);
                                    $(".site-cart .total .money").text("{!! $currency !!}" + data.totamt);
                                } else {
                                    flasher.error("Error"); 
                                }   
                                //reset variant data
                                $(".variation-selector").attr('data-val', "");
                                $(".swatch").removeClass("selected").removeClass("disabled");
                                $(".variation-selector").show(); 
                                $('.spx_pro_box').hide();
                                $("#pidx").val(0);
                                $(".spx_pro_box .sku_val").text("-");
                                $(".spx_pro_box .price_val").text(currsign + "0");
                                //
                                $('.stockm').removeClass('outstock');
                                $('.stockm').addClass('instock');
                                $('#addtocartform .product-form__cart-submit').prop('disabled', false);
                                $('.stockm').html('In Stock');                                
                            },
                            error: function(data){ console.log(data); }
                        });
                    }
                });
                //
                $(".spr-content .spr-form").hide();
                $('.spr-summary-actions-newreview').on('click', function(){
                    $(".spr-content .spr-form").toggle(); 
                });
                //send review
                $('.sub_review').on('click', function(e){
                    e.preventDefault();
                    $.ajax({
                        method: "POST",
                        url: "{{ route('reviewsave') }}",
                        data: $('#new-review-form').serialize(),
                        success: function(data){ if(data.status == "success"){ flasher.success(data.message); }  },
                        error: function(data){ console.log(data); }
                    });
                });
                //wishlist
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
                //quick view
                $('.quick-view').click(function(){
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
                //variant popup
                $(document).on('click', '.variantonly', function(){
                    var idx = $(this).attr('data-val');
                    if(idx == 0){ alert('Please select your product variation'); } else {
                        $('#variantmodal').modal('hide');
                        //$('#mastervariant').html('');
                        //$('#coljsonVariant').html('');
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
                //add to cart - related product
                $(document).on('click', '.addtocartr', function(){
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
                //product inquery
                $('#product_contact').on('submit', function (e) {
                    e.preventDefault();
                    var formData = $(this).serialize();
                    $('#submitBtn').attr('disabled', true).text('Submitting...');
                    $.ajax({
                        url: '{{ route("productinqmail") }}',  // Use the appropriate route
                        method: 'POST',
                        data: formData,
                        success: function(data) {
                            if(data.status == "success"){
                                flasher.success(data.message);
                            } else {
                                flasher.warning(data.message);
                            }
                            $('#product_contact')[0].reset();
                            $('#submitBtn').attr('disabled', false).text('Submit');
                            $.magnificPopup.close();
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                });  
           });
        });
    </script>
    <div class=pswp aria-hidden=true role=dialog tabindex=-1><div class=pswp__bg></div><div class=pswp__scroll-wrap><div class=pswp__container><div class=pswp__item></div><div class=pswp__item></div><div class=pswp__item></div></div><div class="pswp__ui pswp__ui--hidden"><div class=pswp__top-bar><div class=pswp__counter></div><button class="pswp__button pswp__button--close"title="Close (Esc)"></button> <button class="pswp__button pswp__button--zoom"title="Zoom in/out"></button><div class=pswp__preloader><div class=pswp__preloader__icn><div class=pswp__preloader__cut><div class=pswp__preloader__donut></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class=pswp__share-tooltip></div></div><button class="pswp__button pswp__button--arrow--left"title="Previous (arrow left)"></button> <button class="pswp__button pswp__button--arrow--right"title="Next (arrow right)"></button><div class=pswp__caption><div class=pswp__caption__center></div></div></div></div></div>
    <style> 
        .reviewLink .fa { font: normal normal normal 14px / 1 FontAwesome !important; } 
        .spr-review-content { margin: 0; }
        .spr-reviews { padding: 0; }
        .spr-review { border-top: 0px solid #DFDFDF; border-bottom: 1px solid #DFDFDF; }
        .stat_review {
            display: flex; 
            align-items: center;
        }
        #shopify-product-reviews {
            margin: -20px 0 0 0;
        }
        .spr-review:first-child {
            margin-top: 0px; 
        }
        .spr-review:last-child {
            margin-bottom: 24px; 
        }
        .spr-review {
            padding: 10px 0;
        }  
        .spr-form {
            margin: 0 0 24px 0;
            padding: 0 0 24px 0;
            border-top: 0px solid #DFDFDF;
            border-bottom: 1px solid #DFDFDF;
        }  
        .spr-container {
            padding-bottom: 20px;
            border-bottom: 1px solid #DFDFDF;
        }
        .spr-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .new-review-form .row {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center
        }
        .rating>input {
            display: none
        }
        .rating>label {
            position: relative;
            width: 20px;
            font-size: 23px;
            font-weight: 300;
            color: #ff9500;
            cursor: pointer
        }
        .rating>label::before {
            content: "\2605";
            position: absolute;
            opacity: 0
        }
        .rating>label:hover:before,
        .rating>label:hover~label:before {
            opacity: 1 !important
        }
        .rating>input:checked~label:before {
            opacity: 1
        }
        .rating:hover>input:checked~label:before {
            opacity: 0.4
        }
        .rating-submit:hover {
            color: #fff
        }
        .fax {
            font: normal normal normal 14px/1 FontAwesome !important;
        }
        .freeShipMsg .fa, .shippingMsg .fa {
            width: 20px;
            font-size: 15px;
        }
        .social-sharing .fab {
            font-size: 15px;
        }
        .product-template__container .shareRow .wishlist {
            padding-top: 3px;
            margin-bottom: 0px;
        }
    </style>
    @if($product->ptype == 2)
        <style>
            .variation-selector {
                margin-bottom: 10px;
            }
            .variation-selector label {
                font-weight: bold;
            }
            .variation-selectorpop {
                margin-bottom: 10px;
            }
            .variation-selectorpop label {
                font-weight: bold;
            }
            .swatch-container {
                display: flex;
                gap: 10px;
                margin-top: 0px;
            }
            #color-container .swatch {
                width: 30px;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
                align-content: center;
            }
            .swatch {
                color: #333;
                font-size: 12px;
                font-weight: 400;
                line-height: 28px;
                text-transform: capitalize;
                display: flex;
                margin: 0;
                min-width: 30px;
                height: 30px;
                overflow: hidden;
                text-align: center;
                background-color: #f9f9f9;
                padding: 0 10px;
                border: 2px solid #fff;
                box-shadow: 0 0 0 1px #ddd;
                border-radius: 0;
                -ms-transition: all 0.5s ease-in-out;
                -webkit-transition: all 0.5s ease-in-out;
                transition: all 0.5s ease-in-out;
                cursor: pointer;
                align-content: center;
                justify-content: center;
                align-items: center;
            }
            .swatch.selected {
                /* border: 1px solid #000; */
            }
            .swatch.disabled {
                opacity: 0.7;
                cursor: not-allowed;
            }
            .swatch.disabled::before {
                content: "✖";
                height: 15px;
                width: 15px;
                background-color: #000;
                color: #fff;
                text-align: center;
                font-size: 10px;
                position: absolute;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 1;
                border-radius: 100%;
                cursor: not-allowed;
                margin: 6px auto;
            }
            .swatch span {
                font-size: 16px;
                font-weight: bold;
                color: #fff;
            }
            #product-details {
                margin-top: 30px;
                font-size: 18px;
            }
            #price {
                color: #333;
            }
            .clear-button {
                background-color: #ff4d4d;
                color: white;
                border: none;
                font-size: 14px;
                cursor: pointer;
                border-radius: 5px;
                display: block;
                width: 100%;
                /* padding: 10px 20px;
                margin-top: 20px; */
            }
            .swatch.selected::before{
                content: "✓";
                height: 18px;
                width: 18px;
                background-color: #398f14;
                color: #fff;
                text-align: center;
                font-size: 10px;
                position: absolute;
                display: flex;
                align-items: center;
                justify-content: center;
                transform: none;
                bottom: auto;
                opacity: 1;
                visibility: visible;
                left: auto;
                right: auto;
                border-radius: 100%;
                margin: 0px;
                border-width: 0px;
                border-style: initial;
                border-color: initial;
                border-image: initial;
            }
            .product-container {
                width: 80%;
            }
            .product-template__container .prInfoRow > div {
                margin-right: 4%;
            }
        </style>
        <script>
            $(function(){
                $('.spx_pro_box').hide();


                // var masfiled = ["color","size"]; 
                // var variants = [
                //     {color:"#ff0000", size:"#30", sku:"aas-61", stock:80, rprice:61, sprice:60, id:17346713261},
                //     {color:"#ff0000", size:"#32", sku:"aas-62", stock:20, rprice:62, sprice:61, id:17346713262},
                //     {color:"#ff0000", size:"#34", sku:"aas-63", stock:0, rprice:63, sprice:62, id:17346713263},
                //     {color:"#23f90d", size:"#30", sku:"aas-71", stock:30, rprice:71, sprice:70, id:17346713271},
                //     {color:"#23f90d", size:"#32", sku:"aas-72", stock:0, rprice:72, sprice:71, id:17346713272},
                //     {color:"#23f90d", size:"#34", sku:"aas-73", stock:40, rprice:73, sprice:72, id:17346713273}
                // ];


                //var variants = @json($jsonx);
                //console.log(variants);

                var currsign = $("#currency_sign").val();
                //crate html and add in page
                masfiled.forEach(colnm => {
                    var htmlx = '<div class="variation-selector" id="'+colnm+'-selector" data-set="'+colnm+'" data-val=""><label for="'+colnm+'">'+colnm.replace("_", " ")+':</label><div class="swatch-container" id="'+colnm+'-container"></div></div>';
                    $(".product-container").append(htmlx);
                });
                // Initialize the object for each field
                var mast = {};
                masfiled.forEach(colnm => {
                    mast[colnm] = new Set(); 
                    $.each(variants, function(index, variant) {
                        if(variant[colnm] != 0) { mast[colnm].add(variant[colnm]); }
                    });
                    mast[colnm] = [...mast[colnm]];
                });
                // Dynamically add value to variation
                masfiled.forEach(colnm => {
                    $.each(mast[colnm], function(key, val) {
                        if(colnm == "color") {
                            $("#"+colnm+"-container").append('<div class="swatch" style="background-color: '+val+'" data-value="'+val+'"></div>');
                        } else {
                            $("#"+colnm+"-container").append('<div class="swatch" data-value="'+val+'">'+val+'</div>');
                        }
                    });
                });
                // Add class when clicking on variant option
                $(".swatch").on("click", function() {
                    var selectedValue = $(this).data("value");
                    var containerId = $(this).closest(".variation-selector").attr("id");
                    $(this).siblings().removeClass("selected");
                    if($(this).hasClass("selected")) {
                        $(this).removeClass("selected");
                        $("#" + containerId).attr('data-val', "");
                        $("#pidx").val(0);
                    } else {
                        if(!$(this).hasClass("disabled")) {
                            $(this).addClass("selected");
                            $("#" + containerId).attr('data-val', selectedValue);
                        }
                    }
                    updateprice();
                    //$('.spx_pro_box').show();
                    disableUnrelatedVariants(); // Disable unrelated variations
                });
                // Clear selection option
                $("#clear-selection").on("click", function() { 
                    $(".variation-selector").attr('data-val', "");
                    $(".swatch").removeClass("selected").removeClass("disabled");
                    $(".variation-selector").show(); 
                    $('.spx_pro_box').hide();
                    $("#pidx").val(0);
                    $(".spx_pro_box .sku_val").text("-");
                    $(".spx_pro_box .price_val").text(currsign + "0");
                    //
                    //$('.stockm').removeClass('outstock');
                    //$('.stockm').addClass('instock');
                    $('.inventory_icon').removeClass('hideme');
                    $('.inventory_icon_no').addClass('hideme');
                    $('#addtocartform .product-form__cart-submit').prop('disabled', false);
                    $('button#buynow').prop('disabled', false);
                //    $('.stockm').addClass('instock');
                });
                //pricce update
                function updateprice(){ 
                    const selctval = {};
                    $(".variation-selector").each(function() { 
                        var colnm = $(this).attr('data-set');
                        var valnm = $(this).attr('data-val') ? $(this).attr('data-val') : 0;
                        selctval[colnm] = valnm;
                    });
                    //
                    function checkVariant(variant, selctval) {
                        return Object.keys(selctval).every(key => {
                            return variant[key] === selctval[key];
                        });
                    }
                    //
                    const matchedVariants = variants.filter(variant => checkVariant(variant, selctval));
                    if (matchedVariants.length > 0) {
                        const variant = matchedVariants[0];
                        //$("#price").text("Price: $"+ variant.sprice);
                        $(".spx_pro_box .sku_val").text(variant.sku);
                        $("#pidx").val(variant.id);
                        if(variant.sprice == 0) { variant.sprice = variant.rprice; } 
                        $(".spx_pro_box .price_val").text(currsign + variant.sprice);
                        $('.spx_pro_box').show();
                        $("#Quantity").attr('max', variant.stock);
                        //
                        if(variant.stock == 0){ 
                            $('.inventory_icon').addClass('hideme');
                            $('.inventory_icon_no').removeClass('hideme');
                            $('#addtocartform .product-form__cart-submit').prop('disabled', true);
                            $('button#buynow').prop('disabled', true);
                        } else {
                            $('.inventory_icon').removeClass('hideme');
                            $('.inventory_icon_no').addClass('hideme');
                            $('#addtocartform .product-form__cart-submit').prop('disabled', false);
                            $('button#buynow').prop('disabled', false);
                            //$('.stockm').html('In Stock');
                        }
                    } else { 
                        //$("#price").text("Price: $0");
                        $('.inventory_icon').removeClass('hideme');
                        $('.inventory_icon_no').addClass('hideme');
                        $(".spx_pro_box .sku_val").text("-");
                        $(".spx_pro_box .price_val").text(currsign + "0");
                        $('.spx_pro_box').hide();
                    }
                }
                // Disable unrelated variations based on selected values
                function disableUnrelatedVariants() {
                    const selectedValues = {};
                    $(".variation-selector").each(function() {
                        const colnm = $(this).attr('data-set');
                        const valnm = $(this).attr('data-val') || null;
                        selectedValues[colnm] = valnm;
                    });
                    //loop
                    masfiled.forEach(colnm => {
                        $("#" + colnm + "-container .swatch").each(function() {
                            const value = $(this).data("value");
                            if (value !== null) {
                                // Check if the current variant matches the selected ones
                                const hasMatchingVariants = variants.some(variant => {
                                    return variant[colnm] === value && Object.keys(selectedValues).every(key => {
                                        if (key === colnm) return true; // Skip the current field
                                        return selectedValues[key] === null || selectedValues[key] === variant[key];
                                    });
                                });
                                // Add or remove the disabled class
                                if (hasMatchingVariants) {
                                    $(this).removeClass("disabled"); 
                                } else {
                                    $(this).addClass("disabled");
                                }
                            }
                        });
                    });
                }
            });
        </script>
    @endif
@endpush
