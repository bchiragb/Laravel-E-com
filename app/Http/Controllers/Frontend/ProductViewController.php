<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImgs;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Caster\ImgStub;

class ProductViewController extends Controller
{
    protected $currency;
    protected $productshow;

    public function __construct()
    {
        $typex = SiteSetting::where('key', 'productsetting2')->first();
        $this->currency = $typex->val4;
        //
        $countx = SiteSetting::where('key', 'homesetting2')->pluck('val4')->first();
        $this->productshow = $countx;
    }
  
    public function showproduct(string $slug)
    {
        $product = Product::where('slug', $slug)->where('sts', 1)->first();
        if($product->id == "") { abort(404); }
        //echo $product->id.'='.$slug; echo "<br>";
        //$infodatax = SiteSetting::where('key', 'homesetting1')->get();
        //$social = SiteSetting::where('key', 'Social')->orderby('id', 'asc')->get();
        $pro_sett1 = SiteSetting::where('key', 'productsetting1')->first();
        $pro_sett2 = SiteSetting::where('key', 'productsetting2')->first();
        $pro_img = ProductImgs::where('product_id', $product->id)->orderby('id', 'asc')->get();
        $pro_vari = ProductVariant::where('product_id', $product->id)->orderby('id', 'asc')->where('status', 1)->get();
        //$catnm = ProductCategory::where('id', $product->category)->get();
        $coupon = Coupon::where('status', 1)->orderby('eddt', 'asc')->get();
        //
        $review = ProductReview::where('product_id', $product->id)->orderby('id', 'desc')->where('status', 1)->get();
        $ratingx = array(); $reviewhtml = "";
        foreach ($review as $rev){
            $datex = date('M d, Y', strtotime($rev->pdate)); 
            $starx = "";
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rev->star) { // Display filled star (★)
                    $starx .= '<i class="fa fa-star"></i>';
                } else { // Display empty star (☆)
                    $starx .= '<i class="font-13 fa fa-star-o"></i>';
                }
            }
            $ratingx[] = $rev->star;
            $reviewhtml .= '<div class="spr-review">
                <div class="spr-review-header">
                    <span class="product-review spr-starratings spr-review-header-starratings"><span class="reviewLink">
                        '.$starx.'
                    </span>
                    <h3 class="spr-review-header-title">'.$rev->title.'</h3>
                    <span class="spr-review-header-byline"><strong>'.$rev->name.'</strong> ('.$rev->email.') on <strong>'.$datex.'</strong></span>
                </div>
                <div class="spr-review-content">
                    <p class="spr-review-content-body">'.$rev->desc.'</p>
                </div>
            </div>';
        }

        $total_ratings = array_sum($ratingx);
        $num_ratings = count($ratingx);
        if($num_ratings != 0) {
            $average_rating = $total_ratings / $num_ratings;
            $average_rating = round($average_rating, 1);
        } else {
            $average_rating = 0;
        }
        //
        if($product->ptype == 2) { 
            $columns = DB::table('product_variants')->where('product_id', $product->id)->where('status', '1')->first();
            //$columns = ProductVariant::where('product_id', $product->id)->orderby('id', 'asc')->where('status', 1)->first();
            $columnNames = array_keys((array) $columns);
            $restdata = array_slice($columnNames, 9);
            $colnm = "";
            $exp1 = array_map('trim', $restdata);
            foreach($exp1 as $ep){ $colnm .= "`".$ep."`,"; }
            $colnm = substr($colnm, 0, -1);
            //
            //master data array
            $dataarr = DB::table('attributes')->select('id', 'name', 'slug', 'value')->where('parent', '!=', '0')->orderby('id', 'asc')->get();
            $masarr = [];
            foreach($dataarr as $darr){
                $masarr[$darr->id] = array("id" => $darr->id, "name" => $darr->name, "slug" => $darr->slug, "value" => $darr->value);
            }
            // [{ color: "Red", size: "S", price: 11.99, stock: 10 }]
            $proarr = DB::select('SELECT '.$colnm.', sku, stock, rprice, sprice, id FROM product_variants WHERE product_id = '.$product->id.' AND status = 1');
            $jsonx = []; $i = 0;
            foreach($proarr as $key => $pdata){ 
                foreach($pdata as $k1 => $v2){
                    if(in_array($k1, $exp1)) {
                        if($v2 != 0) {
                            $k1 = str_replace('-', '_', $k1);
                            $jsonx[$i][$k1] = $masarr[$v2]['value'];
                        } else {
                            $k1 = str_replace('-', '_', $k1);
                            $jsonx[$i][$k1] = 0;
                        }
                    } else {
                        $jsonx[$i][$k1] = 0;
                    }
                    if(in_array($k1, ['sku', 'stock', 'rprice', 'sprice', 'id'])) {
                        if($k1 == "id"){
                            $jsonx[$i][$k1] = encode_proid_typ($v2, 2); //create unique id with product type
                        } else {
                            $jsonx[$i][$k1] = $v2;
                        }
                    }
                }
                $i++;
            }
            //
            $allValues = [];
            foreach ($jsonx as $record) {
                foreach ($record as $key => $value) {
                    if(!isset($allValues[$key])) { $allValues[$key] = []; } // If the key doesn't exist in the $allValues array, create an empty array for it
                    if($value != 0 || $value != null) { $allValues[$key][] = $value; } // Add the current value to the corresponding key array
                }
            }
            //remove empty array
            $empty_arr = [];
            foreach ($allValues as $key => $values) {
                if (empty($values)) {
                    $empty_arr[] = str_replace('_', '-', $key);
                    unset($allValues[$key]);
                }
            }
            //
            $uniquecolnmx = array_diff($exp1, $empty_arr); //create unique colnm which have value
            $uniquecolnm = array_values($uniquecolnmx); //make key asc and proper order
            $col_nm_arr = array_map(function($value) {
                return str_replace('-', '_', $value); // Remove dashes
            }, $uniquecolnm);
            //print_r($col_nm_arr);
            $coljsonVariant = json_encode($col_nm_arr, true); //for column name
            $jsonVariants = json_encode($jsonx, true); //for whole record
        } else {
            $jsonx = 0;
            $exp1 = 0;
            $coljsonVariant = 0; 
            $jsonVariants = 0;
        }

        //print_r($jsonx);
        //related product as per category
        $productz = Product::where('sts', 1)->where('category', $product->catnm->id)->where('id', '!=', $product->id)->inRandomOrder()->limit(5)->get();
        $relatedproduct = [];
        foreach($productz as $productx){
            //echo $productx['id']; echo "<br>";
            $tagx = gettag($productx['tag'], $productx['id']);
            $pricex = getprice($productx['id'], $productx['ptype'], $this->currency);
            if($productx['ptype'] == 1) {
                $encode_proid = encode_proid_typ($productx['id'], 1);
                $sel_opti = '<button class="btn btn-addto-cart addtocartr" type="button" tabindex="0" value="'.$encode_proid.'" id="skuidx" data-type="1">Add To Cart</button>';
            } else {
                $encode_proid = encode_proid_typ($productx['id'], 2);
                $sel_opti = '<button class="btn btn-addto-cart variantonly" type="button" tabindex="0" data-val="'.$encode_proid.'">Select option</button>';
            }
            //
            $relatedproduct[] = '
            <div class="col-12 item">
                <div class="product-image">
                    <a href="'.route('product', [$productx['slug']]).'" class="">
                        <img class="primary blur-up lazyload" data-src="'.asset($productx['img1']).'" src="'.asset($productx['img1']).'" alt="'.$productx['title'].'" title="'.$productx['title'].'">
                        <img class="hover blur-up lazyload" data-src="'.asset($productx['img2']).'" src="'.asset($productx['img2']).'" alt="'.$productx['title'].'" title="'.$productx['title'].'">
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
                    <div class="product-name">
                        <a href="'.route('product', [$productx['slug']]).'">'.$productx['title'].'</a>
                    </div>
                </div>
            </div>';
        }

        //echo "111";

        return view('frontend.product', compact('product', 'pro_img', 'pro_vari', 'pro_sett1', 'pro_sett2', 'jsonx', 'reviewhtml', 'num_ratings', 'average_rating', 'coljsonVariant', 'jsonVariants', 'relatedproduct',  'coupon'));
    }

    public function getproduct(Request $request){
        $prodata = decode_proid_typ($request->pidx);
        $pro_id = $prodata[0];
        $pro_type = $prodata[1];
        //die(); exit();

        $product = Product::where('id', $pro_id)->first();
        $pro_vari = ProductVariant::where('product_id', $pro_id)->orderby('id', 'asc')->where('status', 1)->get();
        $p_img1 = $product->img1;
        //
        if($pro_type == 1) { 
            $sku_pro = ucfirst($product->sku);
            $stock_pro = $product->stock;
            if($stock_pro > 0) {
                //$stockx = '<span class="instock">In stock, ready to ship</span>';
                $stockx = 1;
            } else {
                $stockx = 0;
                //$stockx = '<span class="outstock hidex">Unavailable</span>';
            }
            //
            $rprice_pro = $this->currency.$product->rprice;
            $sprice_pro = $this->currency.$product->sprice;
            $jsonx = 0;
            $dtype = 1;
            $pval = $request->pidx;
            $sprice_prox = $sprice_pro;
        } elseif ($pro_type == 2) {
            $sku_pro = ucfirst(getdatax($pro_vari, 'sku'));
            //$stock_pro = getlowstock($pro_vari);
            //if($stock_pro > 0) {
            //    $stockx = '<span class="instock stockm">In stock, ready to ship</span>';
            //} else {
            //    $stockx = '<span class="outstock hidex stockm">Unavailable</span>';
            //}
            //$stockx = '<span class="instock stockm">In stock, ready to ship</span>';
            $stockx = 1;
            //
            $pri_pro = getprices($pro_vari);
            if($pri_pro[0] == $pri_pro[1]){
                $rprice_pro = $this->currency.$pri_pro[0]; 
            } else {
                $rprice_pro = $this->currency.$pri_pro[0].'-'.$this->currency.$pri_pro[1]; 
            }

            if($pri_pro[2] == $pri_pro[3]){
                $sprice_pro = $this->currency.$pri_pro[2];
            } else {
                $sprice_pro = $this->currency.$pri_pro[2].'-'.$this->currency.$pri_pro[3];    
            }
            
            
            $sprice_prox = $pri_pro[2];
            //
            $columns = DB::table('product_variants')->where('product_id', $pro_id)->where('status', '1')->first();
            $columnNames = array_keys((array) $columns);
            $restdata = array_slice($columnNames, 9);
            $colnm = "";
            $exp1 = array_map('trim', $restdata);
            foreach($exp1 as $ep){ $colnm .= "`".$ep."`,"; }
            $colnm = substr($colnm, 0, -1);
            $coldata = implode(', ', $exp1);
            //
            //master data array
            $dataarr = DB::table('attributes')->select('id', 'name', 'slug', 'value')->where('parent', '!=', '0')->orderby('id', 'asc')->get();
            $masarr = [];
            foreach($dataarr as $darr){
                $masarr[$darr->id] = array("id" => $darr->id, "name" => $darr->name, "slug" => $darr->slug, "value" => $darr->value);
            }
            // [{ color: "Red", size: "S", price: 11.99, stock: 10 }]
            $proarr = DB::select('SELECT '.$colnm.', sku, stock, rprice, sprice, id FROM product_variants WHERE product_id = '.$product->id.' AND status = 1');
            $jsonx = []; $i = 0;
            foreach($proarr as $key => $pdata){ 
                foreach($pdata as $k1 => $v2){
                    if(in_array($k1, $exp1)) {
                        if($v2 != 0) {
                            $k1 = str_replace('-', '_', $k1);
                            $jsonx[$i][$k1] = $masarr[$v2]['value'];
                        } else {
                            $k1 = str_replace('-', '_', $k1);
                            $jsonx[$i][$k1] = 0;
                        }
                    } else {
                        $jsonx[$i][$k1] = 0;
                    }
                    if(in_array($k1, ['sku', 'stock', 'rprice', 'sprice', 'id'])) {
                        if($k1 == "id"){
                            $jsonx[$i][$k1] = encode_proid_typ($v2, 2); //create unique id with product type
                        } else {
                            $jsonx[$i][$k1] = $v2;
                        }
                    }
                }
                $i++;
            }
            //
            // $jsonVariants = json_encode($jsonx, true);
            // $rty = getvariantcol($pro_id);
            // // Add color first
            // $ordered_wise = [];
            // foreach ($exp1 as $attribute) {
            //     if (strpos($attribute, "color") !== false) {
            //         array_unshift($ordered_wise, $attribute); // Add color first
            //     } else {
            //         $ordered_wise[] = str_replace('-', '_', $attribute);
            //     }
            // }
            // $exp1 = array_map(function($item) {
            //     return str_replace('-', '_', $item);
            // }, $exp1);
            // $coljsonVariant = json_encode($exp1, true);


            $allValues = [];
            foreach ($jsonx as $record) {
                // Loop through each key-value pair in the current record
                foreach ($record as $key => $value) {
                    // If the key doesn't exist in the $allValues array, create an empty array for it
                    if(!isset($allValues[$key])) {
                        $allValues[$key] = [];
                    }
                    // Add the current value to the corresponding key array
                    if($value != 0 || $value != null) {
                        $allValues[$key][] = $value;
                    }
                }
            }
            //print_r($allValues);
            //remove empty array
            $empty_arr = [];
            foreach ($allValues as $key => $values) {
                // If the array is empty, remove the key from the $allValues array
                if (empty($values)) {
                    $empty_arr[] = str_replace('_', '-', $key);
                    unset($allValues[$key]);
                }
            }
            //print_r($allValues);
            //print_r($empty_arr);
            $uniquecolnmx = array_diff($exp1, $empty_arr); //create unique colnm which have value
            $uniquecolnm = array_values($uniquecolnmx); //make key asc and proper order
            $col_nm_arr = array_map(function($value) {
                return str_replace('-', '_', $value); // Remove dashes
            }, $uniquecolnm);
            $coljsonVariant = json_encode($col_nm_arr, true); //for column name
            $jsonVariants = json_encode($jsonx, true); //for whole record
            




            $dtype = 2;
            $pval = 0;
        } else {
            return 1;
        }
        
        //
        $csrfToken = $request->input('_token');
        $peodata = '
            <div id="ProductSection-product-template" class="product-template__container prstyle1">
                <div class="product-single">
                    <a href="javascript:void()" data-dismiss="modal" class="model-close-btn pull-right" title="close"><span class="icon icon anm anm-times-l"></span></a>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="product-details-img">
                                <div class="pl-20"><img src="'.asset($p_img1).'" alt="'.$product->title.'" /></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="product-single__meta">
                                <form method="post" action="#" id="addtocartform" accept-charset="UTF-8" class="product-form product-form-product-template hidedropdown" enctype="multipart/form-data">
                                <h2 class="product-single__title">'.$product->title.'</h2>
                                <div class="prInfoRow">
                                    <div class="product-stock">'.$stockx.'</div>
                                    <div class="product-sku">SKU: <span class="variant-sku">'.$sku_pro.'</span></div>
                                </div>
                                <p class="product-single__price product-single__price-product-template product-price">';

        //if($pri_pro[2] == 0) {
        if($sprice_prox == 0) {
            $peodata .= '
                                    <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                            <span id="ProductPrice-product-template"><span class="money">'.$rprice_pro.'</span></span>
                                    </span>';
        } else {
            $peodata .= '
                                    <span class="visually-hidden">Regular price</span>
                                    <span id="ComparePrice-product-template"><span class="money old-price">'.$rprice_pro.'</span></s>
                                    <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                            <span id="ProductPrice-product-template"><span class="money">'.$sprice_pro.'</span></span>
                                    </span>';
        }
        
        $peodata .= '
                                </p>
                                <input type="hidden" name="_token" value='.csrf_token().'" autocomplete="off">
                                ';
        
            if($product->ptype == 1) {  
                $peodata .= ' <div class="product-single__description rte">'.$product->desc.'</div>';  
            }
            $peodata .= '<div class="product-container"></div>
                                <div class="product-action clearfix">
                                    <div class="product-form__item--submit">
                                        <button type="button" name="add" class="btn product-form__cart-submit addtocart" value="'.$pval.'"><span>Add to cart</span></button>
                                    </div>
                                </div>
                                <div class="swatchxx clearfix spx_pro_box" data-option-index="0" >
                                    <div class="product-form__item">
                                        <div class="product-single__price">SKU: <span class="sku_val product-price__price product-price__sale">-</span></div>
                                        <div class="product-single__price">Price: <span class="price_val product-price__price product-price__sale">{{ $currency }} 0</span></div>
                                    </div>
                                    <div class="swatch-element available"><button class="swatchLbl medium rectangle clearx clear-button" id="clear-selection" title="clear" type="button">clear</button></div>
                                </div>
                                
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <script>$(".spx_pro_box").hide();</script>
        ';

        if($product->ptype == 2) { 
            $peodata .= '
            <div id="mastervariant" class="hideme">'.$jsonVariants.'</div>
            <div id="coljsonVariant" class="hideme">'.$coljsonVariant.'</div>
            <script src="'.asset('fassets/js/product.js').'"></script>';
        }

        return $peodata;
    }

    public function onlyvariant(Request $request){
        //$pro_id  = decode_pro($request->idx);
        $prodata = decode_proid_typ($request->pidx);
        $pro_id = $prodata[0];
        $product = Product::where('id', $pro_id)->first();
        //
        $columns = DB::table('product_variants')->where('product_id', $pro_id)->where('status', '1')->first();
        $columnNames = array_keys((array) $columns);
        $restdata = array_slice($columnNames, 9);
        $colnm = "";
        $exp1 = array_map('trim', $restdata);
        foreach($exp1 as $ep){ $colnm .= "`".$ep."`,"; }
        $colnm = substr($colnm, 0, -1);
        //
        //master data array
        $dataarr = DB::table('attributes')->select('id', 'name', 'slug', 'value')->where('parent', '!=', '0')->orderby('id', 'asc')->get();
        $masarr = [];
        foreach($dataarr as $darr){
            $masarr[$darr->id] = array("id" => $darr->id, "name" => $darr->name, "slug" => $darr->slug, "value" => $darr->value);
        }
        // [{ color: "Red", size: "S", price: 11.99, stock: 10 }]
        $proarr = DB::select('SELECT '.$colnm.', sku, stock, rprice, sprice, id FROM product_variants WHERE product_id = '.$product->id.' AND status = 1');
        $jsonx = []; $i = 0;
        foreach($proarr as $key => $pdata){ 
            foreach($pdata as $k1 => $v2){
                if(in_array($k1, $exp1)) {
                    if($v2 != 0) {
                        $k1 = str_replace('-', '_', $k1);
                        $jsonx[$i][$k1] = $masarr[$v2]['value'];
                    } else {
                        $k1 = str_replace('-', '_', $k1);
                        $jsonx[$i][$k1] = 0;
                    }
                } else {
                    $jsonx[$i][$k1] = 0;
                }
                if(in_array($k1, ['sku', 'stock', 'rprice', 'sprice', 'id'])) {
                    if($k1 == "id"){
                        //$jsonx[$i][$k1] = intval($v2.strtotime('now').'2'); //create unique id with product type
                        $jsonx[$i][$k1] = encode_proid_typ($v2, 2); //create unique id with product type 
                    } else {
                        $jsonx[$i][$k1] = $v2;
                    }
                }
            }
            $i++;
        }
        //print_r($jsonx);
        // create loop for create key array with value
        $allValues = [];
        foreach ($jsonx as $record) {
            // Loop through each key-value pair in the current record
            foreach ($record as $key => $value) {
                // If the key doesn't exist in the $allValues array, create an empty array for it
                if(!isset($allValues[$key])) {
                    $allValues[$key] = [];
                }
                // Add the current value to the corresponding key array
                if($value != 0 || $value != null) {
                    $allValues[$key][] = $value;
                }
            }
        }
        //print_r($allValues);
        //remove empty array
        $empty_arr = [];
        foreach ($allValues as $key => $values) {
            // If the array is empty, remove the key from the $allValues array
            if (empty($values)) {
                $empty_arr[] = str_replace('_', '-', $key);
                unset($allValues[$key]);
            }
        }
        //print_r($allValues);
        //print_r($empty_arr);
        $uniquecolnmx = array_diff($exp1, $empty_arr); //create unique colnm which have value
        $uniquecolnm = array_values($uniquecolnmx); //make key asc and proper order
        $col_nm_arr = array_map(function($value) {
            return str_replace('-', '_', $value); // Remove dashes
        }, $uniquecolnm);
        $coljsonVariant = json_encode($col_nm_arr, true); //for column name
        $jsonVariants = json_encode($jsonx, true); //for whole record
        //
        $csrfToken = $request->input('_token');
        $dtype = 2;
        $pval = 0;
        $peodata = '
            <div class="product-template__container prstyle1">
                <div class="product-single">
                    <a href="javascript:void()" data-dismiss="modal" class="model-close-btn pull-right" title="close"><span class="icon icon anm anm-times-l"></span></a>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="product-single__meta">
                                <input type="hidden" name="_token" value='.csrf_token().'" autocomplete="off">
                                <div class="product-container"></div>
                                <div class="product-action clearfix">
                                    <div class="product-form__item--submit">
                                        <button type="button" class="btn product-form__cart-submit addtocart addtocartr" value="'.$pval.'"><span>Add to cart</span></button>
                                    </div>
                                </div>
                                <div class="swatchxx clearfix spx_pro_box" data-option-index="0" >
                                    <div class="product-form__item">
                                        <div class="product-single__price">Price: <span class="price_val product-price__price product-price__sale">{{ $currency }} 0</span></div>
                                    </div>
                                    <div class="swatch-element available"><button class="swatchLbl medium rectangle clearx clear-button" id="clear-selection" title="clear" type="button">clear</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <script>$(".spx_pro_box").hide();</script>
        ';

        $peodata .= '
        <div id="mastervariant_rel" class="hideme">'.$jsonVariants.'</div>
        <div id="coljsonVariant_rel" class="hideme">'.$coljsonVariant.'</div>
        <script src="'.asset('fassets/js/product_popup.js').'"></script>';
        
        return $peodata;
    }

    //chk is this product or category
    public function squery(String $string){
        $product = Product::where('slug', $string)->where('sts', 1)->first();
        if($product === null) {
            abort(404);
        } else {
            return redirect()->route('product', [$product->slug]);
        }
    }

    //producat with category, filter, sorting
    public function showcategory(Request $request, string $slug){ 
        //some basic details & get product id and name using slug
        $currency = $this->currency;
        $categories = ProductCategory::orderBy('parent')->get();  
        $showcate = 1; 
        //
        $get_sortBy = $request->get('sortby', 'dat_ne_ol'); //get data when click on sorting
        $get_price = $request->get('price', null); //get data when click on price filter
        if ($get_price != "") {
            $get_pricex = explode('-', $get_price);
            $minPrice = isset($get_pricex[0]) ? $get_pricex[0] : '';
            $maxPrice = isset($get_pricex[1]) ? $get_pricex[1] : '';
        } else {
            $minPrice = '';
            $maxPrice = '';
        }
        //
        $get_color = $request->get('color', null); //get data when click on size filter
        $colorarrs = [];
        if($get_color != ""){
            $colorarr = explode(',', $get_color);
            $size_arr = Attribute::whereIn('value', $colorarr)->orderBy('order', 'asc')->get();
            foreach($size_arr as $siarr){
                $colorarrs[] = $siarr->id;
            }
        }
        //
        $get_size = $request->get('size', null); //get data when click on size filter
        $sizearrs = [];
        if($get_size != ""){
            $sizearr = explode(',', $get_size);
            $size_arr = Attribute::whereIn('value', $sizearr)->orderBy('order', 'asc')->get();
            foreach($size_arr as $siarr){
                $sizearrs[] = $siarr->id;
            }
        }

        //if category is valid and have product?
        $catid = []; $catid_str = ""; $catnm = ""; $catimg = ""; $catdesc = "";
        if(strpos($slug, '_') !== false) { //multiple cat
            $slugx = explode('_', $slug);
            foreach($slugx as $slu){
                $catdata = ProductCategory::where('slug', $slu)->first();
                if(!$catdata) {
                    abort(404);
                } else {
                    $catdataArray = $catdata->toArray();
                    $catnm .= $catdata['name'].' & ';
                    $catid[] = $catdata['id'];
                    $catid_str .= $catdata['id'].',';
                    $catimg = $catdata['img']; 
                    $catdesc = $catdata['desc'];
                }
            }
            $catnm = rtrim($catnm, " & ");
            $catid_str = rtrim($catid_str, ", ");
        } else { //single cat
            $catdata = ProductCategory::where('slug', $slug)->first();
            if(!$catdata) {
                abort(404);
            } else {
                $catdataArray = $catdata->toArray();
                $catnm = $catdata['name'];
                $catid[] = $catdata['id'];
                $catid_str .= $catdata['id'];
                $catimg = $catdata['img']; 
                $catdesc = $catdata['desc'];
            }
           
        }
       
        //chk pro cat have 0  product
        $chkproduct = DB::table('products')->whereIn('products.category', $catid)->get();
        $productCount = $chkproduct->count();
        if ($productCount === 0) {
            $color_arr = []; $size_arr = []; $price_arr = [0, 0]; $product_arr = []; $totProduct = 0; 
            return view('frontend.category', compact('slug', 'catnm', 'showcate', 'color_arr', 'size_arr', 'price_arr', 'totProduct', 'currency', 'categories', 'product_arr'));
        } else {
            //query for get min, max price
            $price_rangex = DB::select('SELECT 
                products.id AS product_id, products.ptype, 
                MIN(CASE WHEN products.ptype = 1 THEN products.rprice ELSE NULL END) AS min_simple_price,
                MAX(CASE WHEN products.ptype = 1 THEN products.rprice ELSE NULL END) AS max_simple_price,
                MIN(CASE WHEN products.ptype = 2 THEN product_variants.rprice ELSE NULL END) AS min_variant_price,
                MAX(CASE WHEN products.ptype = 2 THEN product_variants.rprice ELSE NULL END) AS max_variant_price
            FROM  products
            LEFT JOIN product_variants ON products.id = product_variants.product_id
            WHERE products.category IN ('.$catid_str.')
            GROUP BY products.id, products.ptype');
            $price_arr = findminmax($price_rangex);
            
            //if cat have size then show size
            $size_rangex = DB::select('SELECT p.id AS product_id, pv.size, a.value
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id
            LEFT JOIN attributes a ON a.id = pv.size
            WHERE p.category IN ('.$catid_str.') AND pv.size != 0 AND  pv.size != ""
            ORDER BY a.order asc');
            $size_arr = make_range($size_rangex);
            
            //if cat have color then show color
            $color_rangex = DB::select('SELECT p.id AS product_id, pv.color, a.value
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id
            LEFT JOIN attributes a ON a.id = pv.color
            WHERE p.category IN ('.$catid_str.') AND pv.color != 0 AND  pv.color != ""
            ORDER BY a.order asc');
            $color_arr = make_range($color_rangex);
        
            //product details with filter and sortby
            $product_arr = DB::table('products as p')
            ->leftJoin('product_variants as pv', 'p.id', '=', 'pv.product_id')
            ->select(
                'p.id as id',
                DB::raw('GROUP_CONCAT(DISTINCT pv.id) as vid'),
                DB::raw('CONCAT(COALESCE(p.rprice, ""), COALESCE(GROUP_CONCAT(DISTINCT pv.rprice), "")) as merged_rprice'), 
                DB::raw('LEAST(COALESCE(NULLIF(p.rprice, ""), 9999999), MIN(COALESCE(NULLIF(pv.rprice, ""), 9999999))) as min_pricex'),
                DB::raw('LEAST(COALESCE(NULLIF(p.rprice, ""), 9999999), MAX(COALESCE(NULLIF(pv.rprice, ""), 9999999))) as max_pricex'),
                'p.ptype as ptype',
                'p.rprice as p_rprice',
                'p.sprice as p_sprice',
                DB::raw('GROUP_CONCAT(DISTINCT pv.rprice) as variant_rprice'),
                DB::raw('GROUP_CONCAT(DISTINCT pv.sprice) as variant_sprice'),
                DB::raw('GROUP_CONCAT(DISTINCT pv.color) as color'),
                DB::raw('GROUP_CONCAT(DISTINCT pv.size) as size'),
                'p.title', 'p.slug', 'p.tag', 'p.img1', 'p.img2'
            )
            ->whereIn('p.category', $catid) //->whereIn('p.category', [9, 15])
            ->where(function($product_arr) use ($sizearrs) {
                if(!is_null($sizearrs) && !empty($sizearrs)) { 
                    $product_arr->whereIn('pv.size', $sizearrs);
                }
            })
            ->where(function($product_arr) use ($colorarrs) {
                if(!is_null($colorarrs) && !empty($colorarrs)) { 
                    $product_arr->whereIn('pv.color', $colorarrs); 
                }
            })
            ->where(function($product_arr) use ($minPrice, $maxPrice) { 
                if(!empty($minPrice) && !empty($maxPrice) && $minPrice && $maxPrice) {
                    $product_arr->whereBetween('p.rprice', [(int) $minPrice, (int) $maxPrice])
                    ->orWhereBetween('pv.rprice', [(int) $minPrice, (int) $maxPrice]);
                }
            })
            ->groupBy('p.id');

            //sorting
            if ($get_sortBy == 'dat_ne_ol') {
                $product_arr = $product_arr->orderBy('p.id', 'desc');
            } elseif ($get_sortBy == 'dat_ol_ne') {
                $product_arr = $product_arr->orderBy('p.id', 'asc');
            } elseif ($get_sortBy == 'pri_lo_hi') {
                $product_arr = $product_arr->orderBy('min_pricex', 'asc');
            } elseif ($get_sortBy == 'pri_hi_lo') {
                $product_arr = $product_arr->orderBy('min_pricex', 'desc');
            } 

            //pagination
            $product_arr = $product_arr->paginate($this->productshow)->appends(['sizearrs' => $sizearrs, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'get_sortBy' => $get_sortBy]);
            //get total count
            $totProduct = DB::table('products as p')
            ->leftJoin('product_variants as pv', 'p.id', '=', 'pv.product_id')
            ->whereIn('p.category', $catid)
            ->where(function($totProduct) use ($sizearrs) {
                if (!is_null($sizearrs) && !empty($sizearrs)) {
                    $totProduct->whereIn('pv.size', $sizearrs);
                }
            })
            ->where(function($totProduct) use ($colorarrs) {
                if(!is_null($colorarrs) && !empty($colorarrs)) { 
                    $totProduct->whereIn('pv.color', $colorarrs);
                }
            })
            ->where(function($totProduct) use ($minPrice, $maxPrice) {
                if(!empty($minPrice) && !empty($maxPrice) && $minPrice && $maxPrice) {
                    $totProduct->whereBetween('p.rprice', [$minPrice, $maxPrice])
                    ->orWhereBetween('pv.rprice', [$minPrice, $maxPrice]);
                }
            })
            ->distinct() 
            ->count('p.id'); 
            //pagination when call ajax, filter, sorting
            if ($request->ajax()) {
                return response()->json([
                    'products_html' => view('partials.allproducts', compact('slug', 'catnm', 'catimg', 'catdesc', 'showcate', 'color_arr', 'size_arr', 'price_arr', 'totProduct', 'currency', 'categories', 'product_arr'))->render(),
                    'pagination_html' => $product_arr->links('pagination::bootstrap-5')->render(),
                    'totProduct' => $totProduct,
                ]);
            }
            //
            return view('frontend.category', compact('slug', 'catnm', 'catimg', 'catdesc', 'showcate', 'color_arr', 'size_arr', 'price_arr', 'totProduct', 'currency', 'categories', 'product_arr'));
        }    
    }

    public function shopnow(Request $request){
        $catnm = 'Shop';
        $catid = 0;
        $slug = 'shop';
        //some basic details & get product id and name using slug
        $currency = $this->currency;
        $categories = ProductCategory::orderBy('parent')->get();  
        $showcate = 1; 
        //
        $get_sortBy = $request->get('sortby', 'dat_ne_ol'); //get data when click on sorting
        $get_price = $request->get('price', null); //get data when click on price filter
        if ($get_price != "") {
            $get_pricex = explode('-', $get_price);
            $minPrice = isset($get_pricex[0]) ? $get_pricex[0] : '';
            $maxPrice = isset($get_pricex[1]) ? $get_pricex[1] : '';
        } else {
            $minPrice = '';
            $maxPrice = '';
        }
        //
        $get_color = $request->get('color', null); //get data when click on size filter
        $colorarrs = [];
        if($get_color != ""){
            $colorarr = explode(',', $get_color);
            $size_arr = Attribute::whereIn('value', $colorarr)->orderBy('order', 'asc')->get();
            foreach($size_arr as $siarr){
                $colorarrs[] = $siarr->id;
            }
        }
        //
        $get_size = $request->get('size', null); //get data when click on size filter
        $sizearrs = [];
        if($get_size != ""){
            $sizearr = explode(',', $get_size);
            $size_arr = Attribute::whereIn('value', $sizearr)->orderBy('order', 'asc')->get();
            foreach($size_arr as $siarr){
                $sizearrs[] = $siarr->id;
            }
        }

        //query for get min, max price
        $price_rangex = DB::select('SELECT 
            products.id AS product_id, products.ptype, 
            MIN(CASE WHEN products.ptype = 1 THEN products.rprice ELSE NULL END) AS min_simple_price,
            MAX(CASE WHEN products.ptype = 1 THEN products.rprice ELSE NULL END) AS max_simple_price,
            MIN(CASE WHEN products.ptype = 2 THEN product_variants.rprice ELSE NULL END) AS min_variant_price,
            MAX(CASE WHEN products.ptype = 2 THEN product_variants.rprice ELSE NULL END) AS max_variant_price
        FROM  products
        LEFT JOIN product_variants ON products.id = product_variants.product_id
        GROUP BY products.id, products.ptype');
        $price_arr = findminmax($price_rangex);
        
        //if cat have size then show size
        $size_rangex = DB::select('SELECT p.id AS product_id, pv.size, a.value
        FROM products p
        LEFT JOIN product_variants pv ON p.id = pv.product_id
        LEFT JOIN attributes a ON a.id = pv.size
        WHERE pv.size != 0 AND  pv.size != ""
        ORDER BY a.order asc');
        $size_arr = make_range($size_rangex);
        
        //if cat have color then show color
        $color_rangex = DB::select('SELECT p.id AS product_id, pv.color, a.value
        FROM products p
        LEFT JOIN product_variants pv ON p.id = pv.product_id
        LEFT JOIN attributes a ON a.id = pv.color
        WHERE pv.color != 0 AND  pv.color != ""
        ORDER BY a.order asc');
        $color_arr = make_range($color_rangex);
    
        //product details with filter and sortby
        $product_arr = DB::table('products as p')
        ->leftJoin('product_variants as pv', 'p.id', '=', 'pv.product_id')
        ->select(
            'p.id as id',
            DB::raw('GROUP_CONCAT(DISTINCT pv.id) as vid'),
            DB::raw('LEAST(COALESCE(NULLIF(p.rprice, ""), 9999999), MIN(COALESCE(NULLIF(pv.rprice, ""), 9999999))) as min_pricex'),
            'p.ptype as ptype',
            'p.rprice as p_rprice',
            'p.sprice as p_sprice',
            DB::raw('GROUP_CONCAT(DISTINCT pv.rprice) as variant_rprice'),
            DB::raw('GROUP_CONCAT(DISTINCT pv.sprice) as variant_sprice'),
            DB::raw('GROUP_CONCAT(DISTINCT pv.color) as color'),
            DB::raw('GROUP_CONCAT(DISTINCT pv.size) as size'),
            'p.title', 'p.slug', 'p.tag', 'p.img1', 'p.img2'
        )
        ->where(function($product_arr) use ($sizearrs) {
            if(!is_null($sizearrs) && !empty($sizearrs)) { 
                $product_arr->whereIn('pv.size', $sizearrs);
            }
        })
        ->where(function($product_arr) use ($colorarrs) {
            if(!is_null($colorarrs) && !empty($colorarrs)) { 
                $product_arr->whereIn('pv.color', $colorarrs); 
            }
        })
        ->where(function($product_arr) use ($minPrice, $maxPrice) { 
            if(!empty($minPrice) && !empty($maxPrice) && $minPrice && $maxPrice) {
                $product_arr->whereBetween('p.rprice', [(int) $minPrice, (int) $maxPrice])
                ->orWhereBetween('pv.rprice', [(int) $minPrice, (int) $maxPrice]);
            }
        })
        ->groupBy('p.id');

        //sorting
        if ($get_sortBy == 'dat_ne_ol') {
            $product_arr = $product_arr->orderBy('p.id', 'desc');
        } elseif ($get_sortBy == 'dat_ol_ne') {
            $product_arr = $product_arr->orderBy('p.id', 'asc');
        } elseif ($get_sortBy == 'pri_lo_hi') {
            $product_arr = $product_arr->orderBy('min_pricex', 'asc');
        } elseif ($get_sortBy == 'pri_hi_lo') {
            $product_arr = $product_arr->orderBy('min_pricex', 'desc');
        } 

        //pagination
        $product_arr = $product_arr->paginate($this->productshow)->appends(['sizearrs' => $sizearrs, 'minPrice' => $minPrice, 'maxPrice' => $maxPrice, 'get_sortBy' => $get_sortBy]);
        //get total count
        $totProduct = DB::table('products as p')
        ->leftJoin('product_variants as pv', 'p.id', '=', 'pv.product_id')
        ->where(function($totProduct) use ($sizearrs) {
            if (!is_null($sizearrs) && !empty($sizearrs)) {
                $totProduct->whereIn('pv.size', $sizearrs);
            }
        })
        ->where(function($totProduct) use ($colorarrs) {
            if(!is_null($colorarrs) && !empty($colorarrs)) { 
                $totProduct->whereIn('pv.color', $colorarrs);
            }
        })
        ->where(function($totProduct) use ($minPrice, $maxPrice) {
            if(!empty($minPrice) && !empty($maxPrice) && $minPrice && $maxPrice) {
                $totProduct->whereBetween('p.rprice', [$minPrice, $maxPrice])
                ->orWhereBetween('pv.rprice', [$minPrice, $maxPrice]);
            }
        })
        ->distinct() 
        ->count('p.id'); 
        //pagination when call ajax, filter, sorting
        if ($request->ajax()) {
            return response()->json([
                'products_html' => view('partials.allproducts', compact('slug', 'catnm', 'showcate', 'color_arr', 'size_arr', 'price_arr', 'totProduct', 'currency', 'categories', 'product_arr'))->render(),
                'pagination_html' => $product_arr->links('pagination::bootstrap-5')->render(),
                'totProduct' => $totProduct,
            ]);
        }
        //
        return view('frontend.shop', compact('slug', 'catnm', 'showcate', 'color_arr', 'size_arr', 'price_arr', 'totProduct', 'currency', 'categories', 'product_arr')); 
    }

    //change view and save in session
    public function chageListView(Request $request){
       Session::put('product_list_style', $request->style);
    }
}
