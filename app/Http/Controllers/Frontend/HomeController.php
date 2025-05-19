<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        //echo "<pre>";


        //slider, infobox, productsetting2, homesetting2
        $keys = ['slider', 'infobox', 'productsetting2', 'homesetting2'];
        $settings = SiteSetting::whereIn('key', $keys)->orderBy('id', 'asc')->get()->groupBy('key');
        $slider = $settings['slider'];
        $infobox = $settings['infobox'];
        $prodata = $settings->get('productsetting2')->first() ?? null;
        $homes2 = $settings->get('homesetting2')->first() ?? null;

        //$slider = SiteSetting::where('key', 'slider')->where('val5', '1')->get();
        //$infobox = SiteSetting::where('key', 'infobox')->orderby('id', 'asc')->get();
        //$infodatax = SiteSetting::where('key', 'homesetting1')->get();
        //$social = SiteSetting::where('key', 'Social')->orderby('id', 'asc')->get();
        //dd($infodata->toArray());

        //new arriaval
        $pro_cat = ProductCategory::where('status', '1')->where('featured', '1')->limit(5)->get();
        $brands = Brand::where('status', '1')->where('is_featured', '1')->limit(6)->get();
        //
        //$prodata = SiteSetting::where('key', 'productsetting2')->first();
        $currency = $prodata->val4;
        // for new arriaval section
        //$homes2 = SiteSetting::where('key', 'homesetting2')->first();
        $productx = Product::where('sts', 1)->orderby('id','desc')->limit($homes2->val3)->get(); //new arriaval
        $newproductx = [];
        foreach($productx as $product){
            //$tagx = gettag($product['tag'], $product['id']);
            //$pricex = getprice($product['id'], $product['ptype'], $currency);

            $mixfun1 = get_tag_price($product['tag'], $product['id'], $product['ptype'], $currency);
            $tagx = $mixfun1[0];
            $pricex = $mixfun1[1];
            //dd($ss);


            if($product['ptype'] == 1) {
                $encode_proid = encode_proid_typ($product['id'], 1);
                $sel_opti = '<button class="btn btn-addto-cart addtocart" type="button" tabindex="0" value="'.$encode_proid.'" id="skuidx" data-type="1">Add To Cart</button>';
            } else {
                $encode_proid = encode_proid_typ($product['id'], 2);
                $sel_opti = '<button class="btn btn-addto-cart variantonly" type="button" tabindex="0" data-val="'.$encode_proid.'">Select option</button>';
            }
            //
            $newproductx[] = 
                '<div class="col-6 col-sm-2 col-md-3 col-lg-3 item">
                    <div class="product-image">
                        <a href="'.route('product', [$product['slug']]).'" class="grid-view-item__link">
                            <img class="primary blur-up lazyload" data-src="'.asset($product['img1']).'" src="'.asset($product['img1']).'" alt="'.$product['title'].'" title="'.$product['title'].'">
                            <img class="hover blur-up lazyload" data-src="'.asset($product['img2']).'" src="'.asset($product['img2']).'" alt="'.$product['title'].'" title="'.$product['title'].'">
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
                        <div class="product-name"><a href="'.route('product', [$product['slug']]).'">'.$product['title'].'</a></div>
                        <div class="product-price">'.$pricex.'</div>
                    </div>
                </div>';
        }


        // for Featured collection section
        $catlistx = explode(',', $homes2->val1);
        $catdata = ProductCategory::whereIn('id', $catlistx)->get();
        $promas = [];
        foreach($catdata as $cat){
            $productcat = product::where('category', $cat['id'])->limit($homes2->val2)->orderby('id','desc')->get();
            //$productcat = product::orderby('id','desc')->limit($homes2->val2)->get();
            foreach($productcat as $pro){
                $tagx = gettag($pro['tag'], $pro['id']);
                $pricex = getprice($pro['id'], $pro['ptype'], $currency);
                if($pro['ptype'] == 1) {
                    $encode_proid = encode_proid_typ($pro['id'], 1);
                    $sel_opti = '<button class="btn btn-addto-cart addtocart" type="button" tabindex="0" value="'.$encode_proid.'">Add To Cart</button>';
                } else {
                    $encode_proid = encode_proid_typ($pro['id'], 2);
                    $sel_opti = '<button class="btn btn-addto-cart variantonly" type="button" tabindex="0" data-val="'.$encode_proid.'">Select option</button>';
                }
                //
                $promas[$cat['name']][] = 
                    '<div class="col-12 item">
                        <div class="product-image">
                            <a href="'.route('product', [$pro['slug']]).'" class="grid-view-item__link">
                                <img class="primary blur-up lazyload" data-src="'.asset($pro['img1']).'" src="'.asset($pro['img1']).'" alt="'.$pro['title'].'" title="'.$pro['title'].'">
                                <img class="hover blur-up lazyload" data-src="'.asset($pro['img2']).'" src="'.asset($pro['img2']).'" alt="'.$pro['title'].'" title="'.$pro['title'].'">
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
                            <div class="product-name"><a href="'.route('product', [$pro['slug']]).'">'.$pro['title'].'</a></div>
                            <div class="product-price">'.$pricex.'</div>
                        </div>
                    </div>';
            }
        }

        return view('frontend.index', compact('slider', 'pro_cat', 'brands', 'infobox', 'newproductx', 'catdata', 'promas'));
    }
}
