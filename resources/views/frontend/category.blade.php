@extends('frontend.layout.master')

@section('body_content')
@php

$pro_list_class = '';
$pro_list_class_main = '';
$pro_view_style = '';
if(session()->has('product_list_style')) {
    $pro_view_style = session()->get('product_list_style');
    if($pro_view_style == 4){
        $pro_list_class = "col-lg-3";
        $pro_list_class_main = '';
    } else if($pro_view_style == 5){
        $pro_list_class = "col-lg-2";
        $pro_list_class_main = 'shop-grid-5';
    } else if($pro_view_style == 6){
        $pro_list_class = "col-lg-2";
        $pro_list_class_main = '';
    } else {
        abort(404);
    }  
} else {
    $pro_view_style = 4;
    $pro_list_class = "col-lg-3";
}
@endphp

@php     
    //$domain = url()->to('/');
    //$caturl = $domain.'/category/'.$slug;
    ////$caturl = url()->full();
    //$domainx = url()->full();
    //size --- http://estore.test/category/dresses?size=XS,S  -- create url
    
    // if(request()->has('size') || request()->has('color') || request()->has('price') || request()->has('sortby')){
    //     $caturl = urldecode(url()->full());
    //     $queryString = parse_url($caturl, PHP_URL_QUERY);
    //     if(strpos($queryString, 'size') !== false) {
    //         $sizeurlz = $caturl;
    //     } else {
    //         $sizeurlz = $caturl.'&size=';
    //     }
    // } else {
    //     $sizeurlz = $caturl.'/?size=';
    // }
    //echo $sizeurlz;

    // $caturlx = url()->current();
    // $fullurlx = urldecode(url()->full());
    // $site_urlx  = '';

    // if(request()->has('size') || request()->has('color') || request()->has('price') || request()->has('sortby')){
    //     $queryString = parse_url($fullurlx, PHP_URL_QUERY);
    //     if(strpos($queryString, 'size') !== false) {
    //         $sizeurlz = $fullurlx;
    //     } else {
    //         $sizeurlz = $fullurlx.'&size=';
    //     }
    // } else {
    //     $sizeurlz = $fullurlx.'/?size=';
    // }

    
    
    // $sizearr = [];
    // if(request()->has('size')){ 
    //     $sizearr = explode(',', request()->size); //add class for selected item
    //     $sizeurl = $sizeurlz.',';  //url for selected item
    //     //print_r($sizearr);

    // } else {
    //     $sizeurl = $sizeurlz;
    // }

    //echo "<br>";
    //echo $sizeurlz;
    
    ///////////////////////////
    $domain = url()->to('/');
    $caturlx = url()->current();
    //color --- http://estore.test/category/dresses?color=XS,S 

    //price --- http://estore.test/category/dresses?price=XS,S
    $sortby = "dat_ne_ol";
    if(request()->has('sortby')){
        $sortby = request()->sortby;
    }

    //size
    $size_arr_sel = [];
    if(request()->has('size')){
        $size_arrx = request()->size;
        $size_arr_sel = explode(',', $size_arrx);
    }
    //$size_url = $caturlx.'/?size=';
    //print_r($size_arr_sel);

    //price
    if(request()->has('price') && request()->price !=  ''){
        $price = explode('-', request()->price);
        $from = $price[0];
        $to = $price[1];
    } else {
        $from = $price_arr[0];
        $to = $price_arr[1];
    }

    //color
    $color_arr_sel = [];
    if(request()->has('color')){
        $color_arrx = request()->color;
        $color_arr_sel = explode(',', $color_arrx);
    }
@endphp

<div id="page-content" class="category_view">
    <div class="collection-header">
        <div class="collection-hero">
            @if($catimg != "")
                <div class="collection-hero__image"><img class="blur-up lazyload" data-src="{{ asset($catimg) }}" src="{{ asset($catimg) }}" alt="{{ $catnm }} category image" title="{{ $catnm }}" /></div>
            @else
                <div class="collection-hero__image"><img class="blur-up lazyload" data-src="{{ asset('fassets/images/cat-women1.jpg') }}" src="{{ asset('fassets/images/cat-women1.jpg') }}" alt="{{ $catnm }} category image" title="{{ $catnm }}" /></div>
            @endif
            <div class="collection-hero__title-wrapper"><h1 class="collection-hero__title page-width">{{ $catnm }}</h1></div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3 col-lg-2 sidebar filterbar">
                <div class="closeFilter d-block d-md-none d-lg-none"><i class="icon icon anm anm-times-l"></i></div>
                <div class="sidebar_tags">
                    @if($showcate == 1)
                    <div class="sidebar_widget categories filter-widget">
                        <div class="widget-title"><h2>Categories</h2></div>
                        <div class="widget-content">
                            <ul class="sidebar_categories"> 
                                @foreach ($categories as $category)
                                    @if ($category->parent == 0) 
                                        @php  $depth = 1; 
                                            $childCategories = $categories->where('parent', $category->id);
                                        @endphp
                                        <li class="level1 @if($childCategories->isNotEmpty()) sub-level @endif">
                                            <a href="{{ $childCategories->isNotEmpty() ? 'javascript:void(0);' : $domain.'/category/'.$category->slug }}" class="site-nav opener">{{ $category->name }}</a>
                                            @if ($childCategories->isNotEmpty())
                                                <ul class="sublinks">
                                                    <li class="level{{ $depth+1 }} viewall"><a href="{{ $domain.'/category/'.$category->slug }}" class="site-nav">View All {{ $category->name }}</a></li>
                                                    @include('partials.category_sub', ['categories' => $categories, 'parentId' => $category->id, 'level' => $depth])
                                                </ul>                                                
                                            @endif
                                        </li>
                                    @endif
                                    @if ($category->parent == 0)
                                        @php
                                            $parentCategoryIds = $categories->where('parent', 0)->pluck('id')->toArray(); // Skip categories that are already shown as parent categories
                                        @endphp
                                        @if (!in_array($category->id, $parentCategoryIds))
                                            <li class="lvl-1 olypare">
                                                <a href="{{ $domain.'/category/'.$category->slug }}" class="site-nav">{{ $category->name }}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @if($price_arr[0] != 0 && $price_arr[1] != 0)
                    <div class="sidebar_widget filterBox filter-widget price_filter">
                        <div class="widget-title">
                            <h2>Price ({{ $currency }})</h2>
                        </div>
                        <form action="{{url()->current()}}" class="price-filter" id="price-form">
                            <input type="hidden" id="pricerange" min="{!! $price_arr[0] !!}" max="{!! $price_arr[1] !!}" />
                            <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                                <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                            </div>
                            <div class="row">
                                <div class="col-6"><p class="no-margin"><input id="amount" type="text" readonly></p></div>
                                <div class="col-6 text-right margin-25px-top"><button class="btn btn-secondary btn--small" type="submit">Filter</button></div>
                            </div>
                            @if(request()->has('price'))
                                <input type="hidden" class="selectedprice" name="price" id="hidden-price" value="{{ request()->price }}" />
                            @else
                                <input type="hidden" class="selectedprice" name="price" id="hidden-price" value="" />
                            @endif
                        </form>
                    </div>
                    @endif
                    @if(!empty($size_arr)) 
                        <div class="sidebar_widget filterBox filter-widget size-swacthes">
                            <div class="widget-title"><h2>Size</h2></div>
                            <div class="filter-color swacth-list">
                                <ul class="size_attr">
                                    @foreach($size_arr as $si_ar)
                                        @if(in_array($si_ar, $size_arr_sel))
                                            <li><a href="{{ route('category', ['slug' => $slug, 'size' => $si_ar, 'sortby' => request()->get('sortby')]) }}"><span class="swacth-btn checked" value="{{ $si_ar }}">{{ $si_ar }}</span></a></li>
                                        @else
                                            <li><a href="{{ route('category', ['slug' => $slug, 'size' => $si_ar, 'sortby' => request()->get('sortby')]) }}"><span class="swacth-btn" value="{{ $si_ar }}">{{ $si_ar }}</span></a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endempty
                    @if(!empty($color_arr))
                    <div class="sidebar_widget filterBox filter-widget">
                        <div class="widget-title"><h2>Color</h2></div>
                        <div class="filter-color swacth-list clearfix color_arr">
                            @foreach($color_arr as $co_ar)
                                @if(in_array($co_ar, $color_arr_sel))
                                    <span class="swacth-btn checked" value="{{ $co_ar }}" style="background-color: {{ $co_ar }};"></span>
                                @else
                                    <span class="swacth-btn" value="{{ $co_ar }}" style="background-color: {{ $co_ar }};"></span>
                                @endif
                                {{-- <a href="{{ route('category', ['slug' => $slug, 'color' => $co_ar, 'sortby' => request()->get('sortby')]) }}"><span class="swacth-btn" value="{{ $co_ar }}" style="background-color: {{ $co_ar }};"></span></a> --}}
                            @endforeach
                        </div>
                    </div>
                    @endempty
                    <div class="sidebar_widget static-banner">                      
                        <img src="{{ asset('fassets/images/side-banner-2.jpg') }}" alt="offer image" />
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-9 col-lg-10 main-col {{ $pro_list_class_main }}">
                <div class="category-description" style="display:none">
                    <h3>{{ $catnm }} Category Description</h3>
                    <p>{{ $catdesc }}</p>
                </div>
                <div class="productList">
                    <button type="button" class="btn btn-filter d-block d-md-none d-lg-none"> Product Filters</button>
                    <div class="toolbar">
                        <div class="filters-toolbar-wrapper">
                            <div class="row">
                                <div class="col-4 col-md-4 col-lg-4 filters-toolbar__item collection-view-as d-flex justify-content-start align-items-center">
                                    <button class="flex_m m-tooltip m-tooltip--top l4 {{ $pro_view_style == 4 ? 'active' : '' }}" data-column="4" aria-label="4-column">
                                        <svg class="m-svg-icon--small" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 12.5">
                                            <path d="M1 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M4 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M7 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M10 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>                      
                                        <span class="m-tooltip__content">4 columns</span>
                                    </button>
                                    {{-- <button class="flex_m m-tooltip m-tooltip--top l5 {{ $pro_view_style == 5 ? 'active' : '' }}" data-column="5" aria-label="5-column">
                                        <svg class="m-svg-icon--small" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 12.5">
                                            <path d="M1 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M4 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M7 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M10 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M13 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>                      
                                        <span class="m-tooltip__content">5 columns</span>
                                    </button> --}}
                                    <button class="flex_m m-tooltip m-tooltip--top l6 {{ $pro_view_style == 6 ? 'active' : '' }}" data-column="6" aria-label="6-column">
                                        <svg class="m-svg-icon--small" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 12.5">
                                            <path d="M1 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M4 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M7 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M10 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M13 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                            <path d="M16 0 V12.5" stroke="currentColor" stroke-width="1.5"></path>
                                        </svg>
                                        <span class="m-tooltip__content">6 columns</span>
                                    </button>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4 text-center filters-toolbar__item filters-toolbar__item--count d-flex justify-content-center align-items-center">
                                    <span class="filters-toolbar__product-count">There are <span id="total-products">{{ $totProduct }}</span> results in total</span>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4 text-right">
                                    <div class="filters-toolbar__item">
                                        <label for="SortBy" class="hidden">Sort</label>
                                        <select name="SortBy" id="SortBy" class="filters-toolbar__inputx filters-toolbar__input--sort">
                                            <option value="dat_ne_ol" {{ request()->get('sortby') == 'dat_ne_ol' ? 'selected' : '' }}>Date, New to Old</option>
                                            <option value="dat_ol_ne" {{ request()->get('sortby') == 'dat_ol_ne' ? 'selected' : '' }}>Date, Old to New</option>
                                            <option value="pri_lo_hi" {{ request()->get('sortby') == 'pri_lo_hi' ? 'selected' : '' }}>Price, Low to High</option>
                                            <option value="pri_hi_lo" {{ request()->get('sortby') == 'pri_hi_lo' ? 'selected' : '' }}>Price, High to Low</option>
                                        </select>
                                        <input class="collection-header__default-sort" type="hidden" value="manual">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="grid-products grid--view-items">
                        <div class="row" id="products-container">
                            @php
                            // foreach($product_arr as $product){
                            //     $tagx = gettag($product['tag'], $product['id']);
                            //     $pricex = getprice($product['id'], $product['ptype'], $currency);
                            //     if($product['ptype'] == 1) {
                            //         $encode_proid = encode_proid_typ($product['id'], 1);
                            //         $sel_opti = '<button class="btn btn-addto-cart addtocart" type="button" tabindex="0" value="'.$encode_proid.'" id="skuidx" data-type="1">Add To Cart</button>';
                            //     } else {
                            //         $encode_proid = encode_proid_typ($product['id'], 2);
                            //         $sel_opti = '<button class="btn btn-addto-cart variantonly" type="button" tabindex="0" data-val="'.$encode_proid.'">Select option</button>';
                            //     }
                            //     //
                            //     echo 
                            //         '<div class="col-6 col-sm-6 col-md-4 col-lg-3 item">
                            //             <div class="product-image">
                            //                 <a href="'.route('product', [$product['slug']]).'" class="grid-view-item__link">
                            //                     <img class="primary blur-up lazyload" data-src="'.asset($product['img1']).'" src="'.asset($product['img1']).'" alt="'.$product['title'].'" title="'.$product['title'].'">
                            //                     <img class="hover blur-up lazyload" data-src="'.asset($product['img2']).'" src="'.asset($product['img2']).'" alt="'.$product['title'].'" title="'.$product['title'].'">
                            //                     <div class="product-labels rectangular">'.$tagx.'</div>
                            //                 </a>
                            //                 <div class="variants add">'.$sel_opti.'</div>
                            //                 <div class="button-set">
                            //                     <a href="javascript:void(0)" title="Quick View" class="btn-action quick-view" data-val="'.$encode_proid.'"><i class="icon anm anm-search-plus-r"></i></a>
                            //                     <div class="wishlist-btn">
                            //                         <a class="wishlist add-to-wishlist" href="javascript:void(0)" data-val="'.$encode_proid.'"><i class="icon anm anm-heart-l"></i></a>
                            //                     </div>
                            //                 </div>
                            //             </div>
                            //             <div class="product-details text-center">
                            //                 <div class="product-name"><a href="'.route('product', [$product['slug']]).'">'.$product['title'].'</a></div>
                            //                 <div class="product-price">'.$pricex.'</div>
                            //             </div>
                            //         </div>';
                            // }


                            if($totProduct != 0) {
                                foreach($product_arr as $product){
                                    $tagx = gettag($product->tag, $product->id);
                                    $pricex = getprice($product->id, $product->ptype, $currency);
                                    if($product->ptype == 1) {
                                        $encode_proid = encode_proid_typ($product->id, 1);
                                        $sel_opti = '<button class="btn btn-addto-cart addtocart" type="button" tabindex="0" value="'.$encode_proid.'" id="skuidx" data-type="1">Add To Cart</button>';
                                    } else {
                                        $encode_proid = encode_proid_typ($product->id, 2);
                                        $sel_opti = '<button class="btn btn-addto-cart variantonly" type="button" tabindex="0" data-val="'.$encode_proid.'">Select option</button>';
                                    }
                                    //
                                    echo 
                                        '<div class="col-6 col-sm-6 col-md-4 col-lg-3 item">
                                            <div class="product-image">
                                                <a href="'.route('product', [$product->slug]).'" class="grid-view-item__link">
                                                    <img class="primary blur-up lazyload" data-src="'.asset($product->img1).'" src="'.asset($product->img1).'" alt="'.$product->title.'" title="'.$product->title.'">
                                                    <img class="hover blur-up lazyload" data-src="'.asset($product->img2).'" src="'.asset($product->img2).'" alt="'.$product->title.'" title="'.$product->title.'">
                                                    <div class="product-labels rectangular">'.$tagx.'</div>
                                                </a>
                                                <div class="variants add">'.$sel_opti.'</div>
                                                <div class="button-set">
                                                    <a href="javascript:void(0)" title="Quick View" class="btn-action quick-view" data-val="'.$encode_proid.'"><i class="icon anm anm-search-plus-r"></i></a>
                                                    <div class="wishlist-btn">
                                                        <a class="wishlist add-to-wishlist" href="javascript:void(0)" data-val="'.$encode_proid.'"><i class="icon anm anm-heart-l"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-details text-center">
                                                <div class="product-name"><a href="'.route('product', [$product->slug]).'">'.$product->title.'</a></div>
                                                <div class="product-price">'.$pricex.'</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                echo "No products to display";
                            }
                            @endphp
                        </div>                       
                    </div>
                    <div class="paginate" id="pagination-container">
                        @if($totProduct != 0)
                            {!! $product_arr->appends(['sortby' => request()->get('sortby', 'dat_ne_ol'), 'size' => request()->get('size'), 'totProduct' => $totProduct, 'price' => $from.'-'.$to])->links('pagination::bootstrap-5') !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
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

<style>
    .m-tooltip__content {
        display: none;
    }
    button.flex_m {
        background-color: #f2f2f2;
        margin-right: 5px !important;
    }
    .m-svg-icon--small {
        width: 30px;
        height: 20px;
        margin-top: 5px;
        padding: 1px;
        cursor: pointer;
    }
    button.flex_m.m-tooltip.m-tooltip--top {
        padding: 0px;
        margin: 0px;
    }
    button.flex_m.m-tooltip.m-tooltip--top.l6 .m-svg-icon--small {
        padding: 1px 1px 1px 6px;
        width: 36px;
        height: 20px;
    }
    button.flex_m.active {
        border: 1px solid;
    }
    #SortBy {
        background-image: none !important;
    }
    .color_arr span {
        border: 1px solid #ccc !important;
    }

    .paginate .page-item.active .page-link {
        background-color: #000;
        border-color: #000;
    }
    .paginate .pagination li a {
        line-height: 1.40 !important;
        border: 1px solid #e8e9eb !important;
    }
    .page-link {
        line-height: initial;
    }
    .paginate .page-item.active .page-link, .paginate .page-item .page-link {
        font-size: 13px;
        height: 32px;
        width: 32px;
        padding: 7px;
    }
    .paginate .page-link:focus {
        box-shadow: none;
    }
    .paginate .page-link:hover {
        color: #000;
    }
    .filterBox .filter-color .swacth-btn.checked {
        border-color: #000 !important;
    }
</style>
@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            $('#price-form').submit(function(event) {
                event.preventDefault(); // Prevent form submission and page reload
                var priceValue = $("#hidden-price").val();
                // Append price to the URL as a query parameter
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('price', priceValue); // Set the price parameter in the URL
                // Redirect to the updated URL (optional: replace the current history state)
                window.history.pushState({}, '', currentUrl.toString()); // Update the browser's address bar without reloading 
                $.ajax({
                    url: currentUrl.toString(),
                    method: 'GET',
                    success: function(response) {
                        $('#total-products').text(response.totProduct);  
                        $('#products-container').html(response.products_html);  // Update product list
                        $('#pagination-container').html(response.pagination_html);  // Update pagination
                    },
                    error: function(error) {
                        console.log('Error fetching filtered data: ', error);
                    }
                });
                
                // Ensure the hidden input 'price' is included in the form submission
                $(this).find("input[name='price']").val(priceValue);
            });
            // When the sort option changes            
            $('#SortBy').change(function(e) {
                e.preventDefault();
                var sortByValue = $(this).val();  // Get the selected sorting option value
                //var currentUrl = window.location.href.split('?')[0]; // Get current URL (without query params)

                // Add or update the sortby parameter in the URL
                //var newUrl = currentUrl + '?sortby=' + sortByValue;
                //alert(newUrl);
                const currentUrlx = new URL(window.location.href);
                currentUrlx.searchParams.set('sortby', sortByValue);
                window.history.pushState({}, '', currentUrlx);

                // Perform an AJAX request to fetch the data with the new sortby value
                $.ajax({
                    url: currentUrlx,  // Make the AJAX request to the new URL
                    method: 'GET',
                    success: function(response) {
                        // Ensure response contains products_html and pagination_html
                        $('#total-products').text(response.totProduct);  
                        $('#products-container').html(response.products_html);  // Update product list
                        $('#pagination-container').html(response.pagination_html);  // Update pagination
                    },
                    error: function(error) {
                        console.log('Error fetching sorted data: ', error);
                    }
                });
            });


            $('ul.size_attr li a').click(function(event) {
                event.preventDefault(); // Prevent the default anchor link behavior
                var size = $(this).find('.swacth-btn').attr('value'); // Get the value (size) from the clicked link
                var currentUrl = new URL(window.location.href); // Get the current URL
                var sizeParam = currentUrl.searchParams.get('size'); // Get the existing 'size' parameter (comma-separated list)
                var span = $(this).find('.swacth-btn'); // Get the span inside the clicked anchor tag
                span.toggleClass('checked');
                var strgg = '&sortby=';
                var sortByValue = $('#SortBy').val();  // Get the selected sort option

                // If there's already a 'size' parameter in the URL, handle it
                if (sizeParam) {
                    var sizes = sizeParam.split(','); // Split the current size parameter into an array
                    if (sizes.includes(size)) {
                        // If the clicked size is already in the list, remove it
                        sizes = sizes.filter(function(item) {
                            return item !== size; // Filter out the selected size
                        });
                    } else {
                        // If the clicked size is not in the list, add it
                        sizes.push(size);
                    }
                    // If the size list is empty after removal, delete the 'size' parameter from the URL
                    if (sizes.length === 0) {
                        currentUrl.searchParams.delete('size');
                        var strgg = '?sortby=';
                    } else {
                        // Otherwise, update the 'size' parameter in the URL
                        currentUrl.searchParams.set('size', sizes.join(','));
                    }
                } else {
                    // If no 'size' parameter exists, create it with the clicked size
                    currentUrl.searchParams.set('size', size);
                }
                // Update the browser's address bar without reloading the page
                var newUrl = currentUrl.toString() + strgg + sortByValue;
                window.history.pushState({}, '', currentUrl.toString());
                // Perform an AJAX request to fetch the data with the new size and sortby values
                $.ajax({
                    url: newUrl,
                    method: 'GET',
                    success: function(response) {
                        $('#total-products').text(response.totProduct);  
                        $('#products-container').html(response.products_html);  // Update product list
                        $('#pagination-container').html(response.pagination_html);  // Update pagination
                    },
                    error: function(error) {
                        console.log('Error fetching filtered data: ', error);
                    }
                });
            });


            //$('div.filter-color .swacth-btn').click(function(event) {
            $('div.color_arr .swacth-btn').click(function(event) {    
                event.preventDefault();  // Prevent the default anchor link behavior
                var color = $(this).attr('value'); // Get the color value from the clicked span
                var currentUrl = new URL(window.location.href); // Get the current URL
                var colorParam = currentUrl.searchParams.get('color'); // Get the existing 'color' parameter (comma-separated list)
                var span = $(this); // Get the span element
                span.toggleClass('checked'); // Toggle the 'checked' class on the selected color
                
                var strgg = '&sortby='; // Initialize 'sortby' query string
                var sortByValue = $('#SortBy').val();  // Get the selected sort option

                // If there's already a 'color' parameter in the URL, handle it
                if (colorParam) {
                    var colors = colorParam.split(','); // Split the current color parameter into an array
                    if (colors.includes(color)) {
                        // If the clicked color is already in the list, remove it
                        colors = colors.filter(function(item) {
                            return item !== color; // Remove the selected color
                        });
                    } else {
                        // If the clicked color is not in the list, add it
                        colors.push(color);
                    }
                    // If the color list is empty after removal, delete the 'color' parameter from the URL
                    if (colors.length === 0) {
                        currentUrl.searchParams.delete('color');
                        strgg = '?sortby=';  // Adjust the query string
                    } else {
                        // Otherwise, update the 'color' parameter in the URL
                        currentUrl.searchParams.set('color', colors.join(','));
                    }
                } else {
                    // If no 'color' parameter exists, create it with the clicked color
                    currentUrl.searchParams.set('color', color);
                }

                // Update the browser's address bar without reloading the page
                var newUrl = currentUrl.toString() + strgg + sortByValue;
                window.history.pushState({}, '', currentUrl.toString());

                // Perform an AJAX request to fetch the data with the new color and sortby values
                $.ajax({
                    url: newUrl,
                    method: 'GET',
                    success: function(response) {
                        $('#total-products').text(response.totProduct);  
                        $('#products-container').html(response.products_html);  // Update product list
                        $('#pagination-container').html(response.pagination_html);  // Update pagination
                    },
                    error: function(error) {
                        console.log('Error fetching filtered data: ', error);
                    }
                });
            });



            // Handle pagination link clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();  // Prevent default link behavior
                var url = $(this).attr('href');  // Get the URL from the clicked pagination link

                // Ensure sortby parameter is appended to the URL
                var sortByValue = $('#SortBy').val();  // Get the current selected sort option
                var newUrl = new URL(url);  // Create a new URL object from the pagination link
                newUrl.searchParams.set('sortby', sortByValue);  // Add or update the sortby parameter

                $.ajax({
                    url: newUrl.toString(),  // Use the modified URL with sortby
                    method: 'GET',
                    success: function(response) {
                        // Ensure response contains products_html and pagination_html
                        $('#total-products').text(response.totProduct);  
                        $('#products-container').html(response.products_html);
                        $('#pagination-container').html(response.pagination_html);
                    },
                    error: function(error) {
                        console.log('Error loading more data: ', error);
                    }
                });
            });
        });




    </script>
    <script>
        var sign = $("#currency_sign").val();
        function calldata(){
            var currentUrl = window.location.href; // Get the full URL with parameters
            //alert(currentUrl);
            // $.ajax({
            //     //url: '/category/list',  // Define the route to your controller method
            //     //url: '/category/sort',
            //     url: '/category/' + "getproduct",
            //     method: 'GET',
            //     //data: { data: 10 },  // Send the selected sort value
            //     data: { _token: "{{ csrf_token() }}", data: currentUrl },
            //     success: function(response) {
            //         $('#products-container').html('');
            //         $('#products-container').html(response);  // Update the product list or container
            //     },
            //     error: function(error) {
            //         console.log('Error:', error);
            //     }
            // });
        }

        

        $(document).ready(function(){
            $('ul.size_attr li axx').click(function(e) {
                e.preventDefault();
                
                var size = $(this).attr('href').split('size=')[1]; // Get the size parameter from the URL
                var sortByValue = $('#SortBy').val();  // Get the selected sort option
                var currentUrl = window.location.href.split('?')[0]; // Get current URL (without query params)

                // Add or update the size and sortby parameters in the URL
                var newUrl = currentUrl + '?size=' + size + '&sortby=' + sortByValue;

                // Perform an AJAX request to fetch the data with the new size and sortby values
                $.ajax({
                    url: newUrl,
                    method: 'GET',
                    success: function(response) {
                        $('#products-container').html(response.products_html);  // Update product list
                        $('#pagination-container').html(response.pagination_html);  // Update pagination
                    },
                    error: function(error) {
                        console.log('Error fetching filtered data: ', error);
                    }
                });
            });
            //
            $('ul.size_attrxx li a').click(function(event) {
                event.preventDefault(); // Prevent the default anchor link behavior
                var size = $(this).find('.swacth-btn').attr('value'); // Get the value (size) from the clicked link
                var currentUrl = new URL(window.location.href); // Get the current URL
                var sizeParam = currentUrl.searchParams.get('size'); // Get the existing 'size' parameter (comma-separated list)
                var span = $(this).find('.swacth-btn'); // Get the span inside the clicked anchor tag
                span.toggleClass('checked');

                // If there's already a 'size' parameter in the URL, handle it
                if (sizeParam) {
                    var sizes = sizeParam.split(','); // Split the current size parameter into an array
                    if (sizes.includes(size)) {
                        // If the clicked size is already in the list, remove it
                        sizes = sizes.filter(function(item) {
                            return item !== size; // Filter out the selected size
                        });
                    } else {
                        // If the clicked size is not in the list, add it
                        sizes.push(size);
                    }
                    // If the size list is empty after removal, delete the 'size' parameter from the URL
                    if (sizes.length === 0) {
                        currentUrl.searchParams.delete('size');
                    } else {
                        // Otherwise, update the 'size' parameter in the URL
                        currentUrl.searchParams.set('size', sizes.join(','));
                    }
                } else {
                    // If no 'size' parameter exists, create it with the clicked size
                    currentUrl.searchParams.set('size', size);
                }
                // Update the browser's address bar without reloading the page
                window.history.pushState({}, '', currentUrl.toString());
                calldata();
            });
            //
            $('#SortByxxx').on('change', function () {
                const sortValue = $(this).val();
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sortby', sortValue);
                window.history.pushState({}, '', currentUrl);
                calldata();
            });










            
            //
            $("#slider-range").slider({
                range: true,
                min: parseInt($("#pricerange").attr('min'), 10),
                max: parseInt($("#pricerange").attr('max'), 10),
                values: [10, 1000000], // Dynamically set these from the server-side
                slide: function(event, ui) {
                    $("#amount").val(ui.values[0] + "-" + ui.values[1]);
                    $("#hidden-price").val(ui.values[0] + "-" + ui.values[1]); // Update hidden field with selected value
                }
            });
            // Set the initial value for the amount input and the hidden price field
            $("#amount").val($("#slider-range").slider("values", 0) + "-" + $("#slider-range").slider("values", 1));
            $("#hidden-price").val($("#slider-range").slider("values", 0) + "-" + $("#slider-range").slider("values", 1));
            // Remove any previously existing "price" field before form submission
            $('#price-formxx').submit(function(event) {
                event.preventDefault(); // Prevent form submission and page reload

                var priceValue = $("#hidden-price").val();
                // Append price to the URL as a query parameter
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('price', priceValue); // Set the price parameter in the URL
                // Redirect to the updated URL (optional: replace the current history state)
                window.history.pushState({}, '', currentUrl.toString()); // Update the browser's address bar without reloading
                // Ensure the hidden input 'price' is included in the form submission
                $(this).find("input[name='price']").val(priceValue);
                calldata();
                /////////////
                //$(this).find("input[name='price']").val($("#hidden-price").val()); // Only set the price from the hidden input
            });
            //
            $(document).on('click', '.flex_m', function(e){
                e.preventDefault();
                var col = $(this).data('column');
                if(col == 4) {
                    $(".grid-products .row .col-6").addClass('col-lg-3');
                    $(".grid-products .row .col-6").removeClass('col-lg-2');
                    $(".container-fluid .row .main-col").removeClass('shop-grid-5'); 
                    $(".flex_m").removeClass('active'); 
                    $(this).addClass('active'); 
                } else if(col == 5) {
                    $(".grid-products .row .col-6").removeClass('col-lg-3');
                    $(".grid-products .row .col-6").addClass('col-lg-2');
                    $(".container-fluid .row .main-col").addClass('shop-grid-5'); 
                    $(".flex_m").removeClass('active'); 
                    $(this).addClass('active'); 
                } else if(col == 6) {
                    $(".grid-products .row .col-6").removeClass('col-lg-3');
                    $(".grid-products .row .col-6").addClass('col-lg-2');
                    $(".container-fluid .row .main-col").removeClass('shop-grid-5');
                    $(".flex_m").removeClass('active'); 
                    $(this).addClass('active');  
                }
                $.ajax({
                    method: 'GET',
                    url: "{{route('change-list-view')}}",
                    data: {style: col},
                    success: function(data){ }
                })
            });
            //
            $(document).on('click', '.quick-view', function(e) {
                e.preventDefault();
                var idx = $(this).attr('data-val');
                $.ajax({
                    type: "GET",
                    url: "http://estore.test/product",
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
            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                var $this = $(this);
                var idx = $this.attr('data-val');
                $.ajax({
                    method: "POST",
                    url: "http://estore.test/wishlist",
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
            $(document).on('click', '.addtocart', function(e){
                e.preventDefault();
                var idx = $(this).val();
                //var idx = $(this).val();
                if(idx == 0){ alert('Please select your product variation'); } else {
                    var pidx = $(this).attr('data-type');
                    var qtyx = 1;
                    $.ajax({
                        method: "POST",
                        url: "http://estore.test/addtocart",
                        //data: { _token: "IIhpzPdpSIz3A956m7cWqMJPKlDZ0ywYM2P4e7Ua", skuidx:idx, pidx:pidx, quantity:qtyx },
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
                                //$(".site-cart .total .money").text("$" + data.totamt);
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
            $(document).on('click', '.variantonly', function(e){
                e.preventDefault();
                var idx = $(this).attr('data-val');
                if(idx == 0){ alert('Please select your product variation'); } else {
                    $('#variantmodal').modal('hide');
                    $.ajax({
                        method: "GET",
                        url: "http://estore.test/onlyvariant",
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
                                $(".site-cart .total .money").text("$" + data.totamt);
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
