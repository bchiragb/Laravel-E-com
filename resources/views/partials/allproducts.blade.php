@php
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
@endphp
