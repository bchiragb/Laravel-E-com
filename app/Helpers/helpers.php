<?php
use Carbon\Carbon;
use App\models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Type\Integer;

// Check if the device is mobile
if (!function_exists('isMobile')) {
    function isMobile() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // List of common mobile device patterns
        $mobilePatterns = [
            'Android', 'iPhone', 'iPad', 'iPod', 'Windows Phone', 'BlackBerry', 'Opera Mini', 'IEMobile'
        ];

        foreach ($mobilePatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}

// calc save amt and count percentage 
if (!function_exists('saveamt')) {
    function saveamt($realprice, $sellprice) {
        $save_amt = $realprice - $sellprice;
        $save_per = round(($save_amt / $realprice) * 100);
        return [$save_amt, $save_per];
    }
}

// calc date for shipping
if (!function_exists('estdate')) {
    function estdate($datex) {
        $newDate = date('l, j F', strtotime('+'.$datex.' days'));
        return $newDate;
    }
}

// get skus
if (!function_exists('getdatax')) {
    function getdatax($dbset, $find) {
        $result = '';
        foreach($dbset as $rec){
            $result .= $rec->sku.', ';
        }
        return substr($result, 0, -3);
    }
}

// get stock
if (!function_exists('getlowstock')) {
    function getlowstock($dbset) {
        $result = [];
        foreach($dbset as $rec){ $result[] = $rec->stock; }
        asort($result);
        $stock = [];
        foreach($result as $res) { $stock[] = $res; }
        return '';
        //if($stock[0] == 0) { 
        //    return $stock[1];
        //} else {
        //    return $stock[0];
        //}  
    }
}

// get prices
if (!function_exists('getprices')) {
    function getpricesxx($dbset) {
        $rprice = []; $sprice = [];
        foreach($dbset as $rec){
            $rprice[] = $rec->rprice;
            $sprice[] = $rec->sprice;
        }
        asort($rprice);
        asort($sprice);
        $rprice = array_filter($rprice);
        $sprice = array_filter($sprice);
        return array($rprice[0], end($rprice), $sprice[0], end($sprice));
    }

    //function getprices($dbset, $nm1 = "rprice", $nm2 = "sprice") {
    function getprices($dbset) {
        $rprice = []; $sprice = [];
        foreach($dbset as $rec){
            //$rprice[] = $rec->nm1;
            //$sprice[] = $rec->nm2;
            $rprice[] = $rec->rprice;
            $sprice[] = $rec->sprice;
        }
        asort($rprice);
        asort($sprice);
        $rprice = array_filter($rprice);  
        //print_r($rprice);
        //print_r($sprice);
        
        //if(!empty($sprice)) {
        //if(count($sprice) == 0){
        if($sprice[0] != 0){
            $sprice = array_filter($sprice);
            //print_r($sprice);
            return array($rprice[0], end($rprice), $sprice[0], end($sprice));
        } else {
            return array($rprice[0], end($rprice), 0, 0);
        }

        
    
        //die(); exit();
        // array($rprice[0], end($rprice), $sprice[0], end($sprice));
    }

    
    function getprices2($dbset) {
        $rprice = []; $sprice = [];

        foreach($dbset as $rec){
            $rprice[] = $rec['rprice'];
            $sprice[] = $rec['sprice'];
        }
        asort($rprice);
        asort($sprice);
        $rprice = array_filter($rprice);  
        //print_r($rprice);
        //print_r($sprice);
        
        //if(!empty($sprice)) {
        //if(count($sprice) == 0){
        if($sprice[0] != 0){
            $sprice = array_filter($sprice);
            //print_r($sprice);
            return array($rprice[0], end($rprice), $sprice[0], end($sprice));
        } else {
            return array($rprice[0], end($rprice), 0, 0);
        }

        
    
        //die(); exit();
        // array($rprice[0], end($rprice), $sprice[0], end($sprice));
    }
}

// get variantcol
if (!function_exists('getvariantcol')) {
    function getvariantcol($proid) {
        $columns = DB::table('product_variants')->where('product_id', $proid)->where('status', '1')->first();
        $columnNames = array_keys((array) $columns);
        $restdata = array_slice($columnNames, 9);
        // Add color first
        $ordered_wise = [];
        foreach ($restdata as $attribute) {
            if (strpos($attribute, "color") !== false) {
                array_unshift($ordered_wise, $attribute); // Add color first
            } else {
                $ordered_wise[] = str_replace('-', '_', $attribute);
            }
        }
        $restdata = array_map(function($item) {
            return str_replace('-', '_', $item);
        }, $restdata);
        return array(implode(', ', $restdata), $ordered_wise);
    }
}

// get variantion details - get selected variant details
if (!function_exists('getvariationdata')) {
    function getvariationdata($proid, $br) {
        //column data
        $columns = DB::table('product_variants')->where('id', $proid)->first();
        $array = json_decode(json_encode($columns), true);
        $slicedarr = array_slice($array, 9); // Start at index 1, take 3 elements
        //master data array
        $dataarr = DB::table('attributes')->select('id', 'name', 'slug', 'value')->where('parent', '!=', '0')->orderby('id', 'asc')->get();
        $masarr = [];
        foreach($dataarr as $darr){
            $masarr[$darr->id] = array("id" => $darr->id, "name" => $darr->name, "slug" => $darr->slug, "value" => $darr->value);
        }   
        //manage
        $dset = "";
        foreach($slicedarr as $key => $val){
            $colnm = str_replace('-', ' ', $key);
            if($val != 0){
                if($br == 1){
                    $dset .= '<b>'.$colnm.'</b>: '.$masarr[$val]['name'].'<br>';
                } else {
                    $dset .= '<b>'.$colnm.'</b>: '.$masarr[$val]['name'].' ';
                }
            }                
        }
        return $dset;
    }
}

// get variant xxxx
if (!function_exists('getcoldata')) {
    function getcoldata($pdata, $pcol, $proid) {
        if(strpos($pcol, "color") !== false){
            $expz = explode(',', $pcol);
            $ordered_attributes = [];
            foreach ($expz as $attribute) {
                if (strpos($attribute, "color") !== false) {
                    array_unshift($ordered_attributes, $attribute); // Add color first
                } else {
                    $ordered_attributes[] = $attribute;
                }
            }
            $pcol = implode(", ", $ordered_attributes);
        } 
        $colnm = "";
        $exp1 = explode(',', $pcol);
        $exp1 = array_map('trim', $exp1);
        foreach($exp1 as $ep){ $colnm .= "`".$ep."`,"; }
        $colnm = substr($colnm, 0, -1);

        //master data array
        $dataarr = DB::table('attributes')->select('id', 'name', 'slug', 'value')->where('parent', '!=', '0')->orderby('id', 'asc')->get();
        $masarr = [];
        foreach($dataarr as $darr){
            $masarr[$darr->id] = array("id" => $darr->id, "name" => $darr->name, "slug" => $darr->slug, "value" => $darr->value);
        }
        //$proarr = DB::table('product_variants')->select($colnm)->where('product_id', $proid)->where('status', '1')->get();
        //$proarr = DB::select('SELECT '.$colnm.' FROM product_variants WHERE product_id = '.$proid.' AND status = 1');
        $proarr = DB::select('SELECT '.$colnm.', sku, stock, rprice, sprice, id FROM product_variants WHERE product_id = '.$proid.' AND status = 1');
        $dataset = [];
        foreach ($proarr as $row) {
            foreach($exp1 as $col){
                if($row->$col != 0) {
                    $dataset[$col][] = $row->$col;
                }
                asort($dataset[$col]);  
            } 
        }
        $dataset = array_map('array_unique', $dataset);
        // [{ color: "Red", size: "S", price: 11.99, stock: 10 }]
        $jsonx = []; $i = 0;
        foreach($proarr as $key => $pdata){ 
            foreach($pdata as $k1 => $v2){
                if(in_array($k1, $exp1)) {
                    if($v2 != 0) {
                        $k1 = str_replace('-', '_', $k1);
                        $jsonx[$i][$k1] = $masarr[$v2]['value'];
                    } else {
                        $jsonx[$i][$k1] = 0;
                    }
                } else {
                    $jsonx[$i][$k1] = 0;
                }
                if(in_array($k1, ['sku', 'stock', 'rprice', 'sprice', 'id'])) {
                    if($k1 == "id"){
                        $jsonx[$i][$k1] = intval(strtotime('now').$v2);
                    } else {
                        $jsonx[$i][$k1] = $v2;
                    }
                }
            }
            $i++;
        }


        //print_r($jsonx);

        $rawx = json_encode($jsonx);
        $json_data = preg_replace('/"([^"]+)":/', '$1:', $rawx);        
        return [$masarr, $dataset, $jsonx, $json_data];
    }
}


// get catnm xxxx
if (!function_exists('variantnm')) {
    function variantnm($pcol) {
        if(strpos($pcol, "color") !== false){
            $expz = explode(',', $pcol);
            $ordered_attributes = [];
            foreach ($expz as $attribute) {
                if (strpos($attribute, "color") !== false) {
                    array_unshift($ordered_attributes, $attribute); // Add color first
                } else {
                    $ordered_attributes[] = $attribute;
                }
            }
            $pcol = implode(", ", $ordered_attributes);
        } 
        $colnm = "";
        $exp1 = explode(',', $pcol);
        $exp1 = array_map('trim', $exp1);
        foreach($exp1 as $ep){ $colnm .= "`".$ep."`,"; }
        $colnm = substr($colnm, 0, -1);
    }
}

//get product id
if (!function_exists('proidget')) {
    function proidget($rawid){
        return substr($rawid, 0, -10);
    }
}

//encode product id
if (!function_exists('proidhide')) {
    function proidhide($rawid){
        return $rawid.strtotime('now');
    }
}

///////////////////////////////////////////////////////////////
//decode product id
if (!function_exists('decode_pro')) {
    function decode_pro($rawid){
        return substr($rawid, 0, -10);
    }
}

//decode product id
if (!function_exists('decode_proid')) {
    function decode_proid($rawid){
        return substr($rawid, 0, -11);
    }
}

//encode product id
if (!function_exists('encode_pro')) {
    function encode_pro($rawid){
        return $rawid.strtotime('now');
    }
}

//encode product id & type
if (!function_exists('encode_proid_typ')) {
    function encode_proid_typ($rawid, $type = 1){
        return intval($rawid.strtotime('now').$type); //2 for variant product
    }
}

//decode product id & type
if (!function_exists('decode_proid_typ')) {
    function decode_proid_typ($rawid){
        $protype = substr($rawid, -1);
        $lastTenDigits = substr($rawid, -11);
        $proid = substr($rawid, 0, -11);
        return [$proid, $protype];
    }
}
///////////////////////////////////////////////////////////////

//get remain ship amt
if (!function_exists('calcfreeship')) {
    function calcfreeship(){
        $columns = DB::table('site_settings')->select('val1')->where('key', 'productsetting1')->first();
        $shipamt = $columns->val1;
        $finaltotx = str_replace(".00", "", Cart::priceTotal()); 
        $finaltot = str_replace(",", "", $finaltotx); 
        if($shipamt < $finaltot){ 
            $retval = $shipamt - $finaltot;
            if($retval > 0) { return 0; }
        } else { 
            $retval = $shipamt - $finaltot;
            return $retval;
        }
    }
}

//get product tag
if(!function_exists('gettag')) {
    function gettag($protag, $proid){
        $taglistx = "";
        //$product = DB::table('products')->where('id', $proid)->first();


        // $productx = Product::with(['variantjoin' => function($queryx) {
        //     $queryx->where('status', 1)->orderBy('id', 'asc');
        // }])->where('id', $proid)->first();
        
        // if($productx->ptype == 2) {
        //     $pri_prox = getprices($productx->variantjoin);
        //     echo $productx->ptype.'--';
        //     if($pri_prox[3] != '') {
        //         echo $pri_prox[1].'--'.$pri_prox[3]; echo "<br>";
        //     }

        // } else {
        //     echo $productx->ptype.'--'.$productx->sprice.'--'.$productx->rprice; echo "<br>";
        // }

        // $product = Product::with(['variantjoin' => function($queryx) {
        //     $queryx->where('status', 1)->orderBy('id', 'asc');
        // }])->where('id', $proid)->first();

        // if($product->ptype == 1) {
        //     if($product->sprice != '') {
        //         $saveamtper = saveamt($product->rprice, $product->sprice);
        //         if($saveamtper[1] != '100') {
        //             $taglistx .= '<span class="lbl pr-label3 c1">-'.$saveamtper[1].'%</span>';  
        //         }
        //     }
        // } else {
        //     $pri_pro = getprices($product->variantjoin);
        //     if($pri_pro[3] != '') {
        //         $saveamtper = saveamt($pri_pro[1], $pri_pro[3]);
        //         if($saveamtper[1] != '100') {
        //             $taglistx .= '<span class="lbl pr-label3 c2">-'.$saveamtper[1].'%</span>'; 
        //         }
        //     } 
        // }



        // SELECT pp.id AS pp_id, pp.rprice AS pp_rprice, pp.sprice AS pp_sprice, pp.ptype,
        // pv.id AS pv_id, pv.rprice AS pv_rprice, pv.sprice AS pv_sprice
        // FROM products AS pp
        // LEFT JOIN product_variants AS pv ON pv.product_id = pp.id
        // WHERE (pv.STATUS = 1 OR pp.sts = 1 ) AND (pp.id = 2 OR pv.product_id = 2)
        // ORDER BY pv.id ASC

        //use Illuminate\Support\Facades\DB;
        
        //dd($proid);

        $productx = DB::table('products as pp')
        ->leftJoin('product_variants as pv', 'pv.product_id', '=', 'pp.id')
        ->where(function ($query) {
            $query->where('pv.STATUS', 1)->orWhere('pp.sts', 1);
        })
        ->where(function ($query) use ($proid) {
            $query->where('pp.id', $proid)->orWhere('pv.product_id', $proid);
        })
        ->orderBy('pv.id', 'asc')
        ->select(
            'pp.id as pp_id', 'pp.rprice as pp_rprice', 'pp.sprice as pp_sprice', 'pp.ptype',
            'pv.id as pv_id', 'pv.rprice as pv_rprice', 'pv.sprice as pv_sprice'
        )->get();

        $vari_pri = []; $ptype = 0;
        foreach($productx as $product) {
            $ptype = $product->ptype;
            if($product->ptype == 1) {
                if($product->pp_sprice != '') {
                    $saveamtper = saveamt($product->pp_rprice, $product->pp_sprice);
                    if($saveamtper[1] != '100') {
                        $taglistx .= '<span class="lbl pr-label3 c1">-'.$saveamtper[1].'%</span>';  
                    }
                } 
            } else  {
                $vari_pri[] = array("rprice" => $product->pv_rprice, "sprice" => $product->pv_sprice);
            }
        }

        //echo  "<pre>"; print_r($vari_pri); die(); exit;
        if($ptype == 2) {
            $pri_pro = getprices2($vari_pri);
            if($pri_pro[3] != '') {
                $saveamtper = saveamt($pri_pro[1], $pri_pro[3]);
                if($saveamtper[1] != '100') {
                    $taglistx .= '<span class="lbl pr-label3 c2">-'.$saveamtper[1].'%</span>'; 
                }
            } 
        }

        
        
        
        /* $product = Product::where('id', $proid)->first();
        $ptype = $product->ptype;
        if($product->ptype == 1) {
            if($product->sprice != '') {
                $saveamtper = saveamt($product->rprice, $product->sprice);
                if($saveamtper[1] != '100') {
                    $taglistx .= '<span class="lbl pr-label3 c1">-'.$saveamtper[1].'%</span>';  
                }
            }
        } else {
            $pro_vari = ProductVariant::where('product_id', $proid)->orderby('id', 'asc')->where('status', 1)->get();
            $pri_pro = getprices($pro_vari);
            if($pri_pro[3] != '') {
                $saveamtper = saveamt($pri_pro[1], $pri_pro[3]);
                if($saveamtper[1] != '100') {
                    $taglistx .= '<span class="lbl pr-label3 c2">-'.$saveamtper[1].'%</span>'; 
                }
            } 
        } */

        foreach (explode(',', $protag) as $tag) {
            if($tag == 1) { $tagnmx[] = "NEW";
                $taglistx .= '<span class="lbl pr-label1">NEW</span>';
            } elseif ($tag == 2) { $tagnmx[] = "HOT";
                $taglistx .= '<span class="lbl pr-label2">HOT</span>';
            } elseif ($tag == 3) { $tagnmx[] = "POPULAR";
                $taglistx .= '<span class="lbl pr-label3">POPULAR</span>';                                            
            }   
        }
        return $taglistx;
    }
}



function get_tag_price($ptag, $pid, $ptype, $pcurrency){
    $taglistx = "";
    if($ptype == 1) {
        $product = Product::where('id', $pid)->first();
        if($product->sprice != '') {
            $saveamtper = saveamt($product->rprice, $product->sprice);
            if($saveamtper[1] != '100') {
                $taglistx .= '<span class="lbl pr-label3 c1">-'.$saveamtper[1].'%</span>';  
            }
        }
        //price  cccccccccccccc
        $rprice_pro = $product->rprice;
        $sprice_pro = $product->sprice;
        if($product->sprice != '') {
            $pricedata = '<span class="old-price">'.$pcurrency.$rprice_pro.'</span><span class="price">'.$pcurrency.$sprice_pro.'</span>';
        } else {
            $pricedata = '<span class="price">'.$pcurrency.$rprice_pro.'</span>';
        } 
    } else {
        $pro_vari = ProductVariant::where('product_id', $pid)->orderby('id', 'asc')->where('status', 1)->get();
        $pri_pro = getprices($pro_vari);
        if($pri_pro[3] != '') {
            $saveamtper = saveamt($pri_pro[1], $pri_pro[3]);
            if($saveamtper[1] != '100') {
                $taglistx .= '<span class="lbl pr-label3 c2">-'.$saveamtper[1].'%</span>'; 
            }
        } 
        //price  cccccccccccccc
        $pri_pro = getprices($pro_vari);
        $rprice_pro = $pcurrency.$pri_pro[0].'-'.$pcurrency.$pri_pro[1];
        $sprice_pro = $pcurrency.$pri_pro[2].'-'.$pcurrency.$pri_pro[3];
        if($pri_pro[2] != 0) { 
            $pricedata = '<span class="old-price">'.$rprice_pro.'</span><span class="price">'.$sprice_pro.'</span>';
        } else {
            $pricedata = '<span class="price simprice">'.$rprice_pro.'</span>';
        } 
    }
    foreach (explode(',', $ptag) as $tag) {
        if($tag == 1) { $tagnmx[] = "NEW";
            $taglistx .= '<span class="lbl pr-label1">NEW</span>';
        } elseif ($tag == 2) { $tagnmx[] = "HOT";
            $taglistx .= '<span class="lbl pr-label2">HOT</span>';
        } elseif ($tag == 3) { $tagnmx[] = "POPULAR";
            $taglistx .= '<span class="lbl pr-label3">POPULAR</span>';                                            
        }   
    }
    //return $taglistx;

    // if($ptype == 1) {
    //     $product = Product::where('id', $pid)->first();
    //     $rprice_pro = $product->rprice;
    //     $sprice_pro = $product->sprice;
    //     if($product->sprice != '') {
    //         $pricedata = '<span class="old-price">'.$pcurrency.$rprice_pro.'</span><span class="price">'.$pcurrency.$sprice_pro.'</span>';
    //     } else {
    //         $pricedata = '<span class="price">'.$pcurrency.$rprice_pro.'</span>';
    //     }   
    // } else { 
    //     $pro_vari = ProductVariant::where('product_id', $pid)->orderby('id', 'asc')->where('status', 1)->get();
    //     $pri_pro = getprices($pro_vari);
    //     $rprice_pro = $pcurrency.$pri_pro[0].'-'.$pcurrency.$pri_pro[1];
    //     $sprice_pro = $pcurrency.$pri_pro[2].'-'.$pcurrency.$pri_pro[3];
    //     //print_r($pri_pro);
    //     //echo "====<br>";
    //     if($pri_pro[2] != 0) { 
    //         $pricedata = '<span class="old-price">'.$rprice_pro.'</span><span class="price">'.$sprice_pro.'</span>';
    //     } else {
    //         $pricedata = '<span class="price simprice">'.$rprice_pro.'</span>';
    //     } 
    // }
    //return $pricedata;
    return [$taglistx, $pricedata];

}


//get product price
if(!function_exists('getprice')) {
    function getprice($proid, $ptype, $currency){
        if($ptype == 1) {
            $product = Product::where('id', $proid)->first();
            $rprice_pro = $product->rprice;
            $sprice_pro = $product->sprice;
            if($product->sprice != '') {
                $pricedata = '<span class="old-price">'.$currency.$rprice_pro.'</span><span class="price">'.$currency.$sprice_pro.'</span>';
            } else {
                $pricedata = '<span class="price">'.$currency.$rprice_pro.'</span>';
            }   
        } else { 
            $pro_vari = ProductVariant::where('product_id', $proid)->orderby('id', 'asc')->where('status', 1)->get();
            $pri_pro = getprices($pro_vari);
            $rprice_pro = $currency.$pri_pro[0].'-'.$currency.$pri_pro[1];
            $sprice_pro = $currency.$pri_pro[2].'-'.$currency.$pri_pro[3];
            //print_r($pri_pro);
            //echo "====<br>";
            if($pri_pro[2] != 0) { 
                $pricedata = '<span class="old-price">'.$rprice_pro.'</span><span class="price">'.$sprice_pro.'</span>';
            } else {
                $pricedata = '<span class="price simprice">'.$rprice_pro.'</span>';
            } 
        }

        return $pricedata;
    }
}

//genrate uniq id for order
if(!function_exists('generateOrderId')) {
    function generateOrderId() {
        $timestamp = time();        
        $randomNumber = mt_rand(10000, 99999);        
        $orderId = substr($timestamp, -5) . $randomNumber;        
        $idx = substr($orderId, 0, 10);
        //
        $columns = DB::table('order')->select('order_id')->where('order_id', $idx)->first();
        if($columns) {
            generateOrderId();
        } else {
            return $idx;
        }

        return $idx;
    }
}

//find country
if(!function_exists('getcountrynm')) {
    function getcountrynm($id) {
        if(!empty($id) && $id != 0) {
            $country = DB::table('countries')->select('name')->where('id', $id)->first();
            return $country->name;
        } else { return "-"; }
    }
}

//find city
if(!function_exists('getstatenm')) {
    function getstatenm($id) {
        if(!empty($id) && $id != 0) {
            $state = DB::table('states')->select('name')->where('id', $id)->first();
            return $state->name;
        } else { return "-"; }
    }
}

//return price without comman and .00
if(!function_exists('beau_price')) {
    function beau_price($amt) {
        $prix = str_replace(',', '', $amt);
        return $prix = str_replace('.00', '', $prix);
    }
}

//return order type
if(!function_exists('order_sts')) {
    function order_sts($id) {
        switch ($id) {
            case 1: //processed successfully and the store has confirmed
                    return "Complete"; 
                break;
            case 2: //dispatched from the store's warehouse
                    return "Shipped"; 
                break;
            case 3: //successfully delivered to the address
                    return "Delivered"; 
                break;
            case 4: //canceled either by you or the store
                    return "Cancelled"; 
                break;
            case 5: //agreed to issue a refund for your order
                    return "Refund"; 
                break;
            case 6: //waiting for payment to process or confirm order
                    return "Pending Payment"; 
                break;
            case 7: //processing the order, like a payment failure or technical error
                    return "Failed"; 
                break;
            case 8: //returned to the store, usually after a return request or issue with the product
                    return "Item Returned"; 
                break;
            default:
                return "Error"; 
                break;
        }
    }
}

//return currency sign
if(!function_exists('currencyx')) {
    function currencyx() {
        $prodata = SiteSetting::where('key', 'productsetting2')->first();
        return $prodata->val4;
    }
}

// Set Sidebar item active 
if(!function_exists('setActive')) {
    function setActive(array $route){
        if(is_array($route)){
            foreach($route as $r){
                if(Request::url() == route($r)){ //if(request()->routeIs($r)){
                    return 'active';
                } 
            }
        }
    }
}

//for header menu frontside - save data in db
if(!function_exists('createArrayWithParent')) {
    function createArrayWithParent(array $dataset, int $parent = 0, int $startId = 1) {
        $finalval = [];
        $currentId = $startId; 
        //
        foreach ($dataset as $key => &$data) {
            $data['parent'] = $parent;
            $data['id'] = $currentId;
            $finalval[] = [
                'text' => $data['text'],
                'href' => $data['href'],
                'icon' => $data['icon'],
                'target' => $data['target'],
                'title' => $data['title'],
                'tag_name' => $data['tag_name'],
                'css_class' => $data['css_class'],
                'tag_color' => $data['tag_color'],
                'parent' => $parent,
                'depth' => $currentId,
            ];
            //
            if(isset($data['children']) && is_array($data['children']) && !empty($data['children'])) {
                $children = createArrayWithParent($data['children'], $currentId, $currentId + 1); // Recursively process the children and merge the results with the current data
                $finalval = array_merge($finalval, $children);
            } 
            //else {
                $currentId++;
            //}
        }
        return $finalval;
    }
}

//for header menu frontside - make data in json for view use
if(!function_exists('buildMenuTree')) {
    function buildMenuTree($items) { 
        $array = json_decode(json_encode($items), true);    
        $menu = [];
        $lookup = [];
        //
        foreach ($array as $item) {
            $item['children'] = [];
            $lookup[$item['id']] = [
                'text' => $item['text'],
                'href' => $item['href'],
                'target' => $item['target'],
                'tag_name' => $item['tag_name'],
                'tag_color' => $item['tag_color'],
                'css_class' => $item['css_class'],
                //'parent' => $item['parent'],
                //'child' => $item['children'],
                //'depth' => $item['depth'],
                //'children' => []
            ];
        }
        //Build tree
        foreach ($array as $item) {
            if ($item['parent'] == 0) {
                $menu[] = &$lookup[$item['id']];
            } else {
                $lookup[$item['parent']]['children'][] = &$lookup[$item['id']];
            }
        }
        //$menuTree = json_encode($menu, JSON_PRETTY_PRINT);
        $menu = json_encode($menu, true);
        return $menu;
    }
}

// app/Helpers/helpers.php
if(!function_exists('greet')) {
    function greet($name) {
        return "Hello, $name!";
    }
}


//get front menu html
if(!function_exists('getmenu')) {
    function getmenu($type) {
        $menux = DB::table('menu_items')->select('id', 'parent',
            'name as text', 'link as href', 'link_type as target', 'tag_name', 'tag_color', 'cssclass as css_class', 'depth',
            DB::raw('CASE WHEN parent = 0 THEN 0 ELSE parent END as children')
        )->get()->toArray();
        $array = json_decode(json_encode($menux), true);    
        $menu = [];
        $lookup = [];
        //
        foreach ($array as $item) {
            $item['children'] = [];
            $lookup[$item['id']] = [
                'text' => $item['text'],
                'href' => $item['href'],
                'target' => $item['target'],
                'tag_name' => $item['tag_name'],
                'tag_color' => $item['tag_color'],
                'css_class' => $item['css_class'],
            ];
        }
        //Build tree
        foreach ($array as $item) {
            if ($item['parent'] == 0) {
                $menu[] = &$lookup[$item['id']];
            } else {
                $lookup[$item['parent']]['children'][] = &$lookup[$item['id']];
            }
        }

        if($type == 1) { //desktop
            $menux = buildMenu_Desk($menu);
        } else if($type == 2) { //mobile
            $menux = buildMenu_Mobi($menu);
        }
        
        return $menux;
    }
}

// menu html for desktop
if(!function_exists('buildMenu_Desk')) {
    function buildMenu_Desk(array $items, int $depth = 0): string
    {
        $levelClasses = [
            0 => 'first_ul',
            1 => 'dropdown first_child_ul',
            2 => 'dropdown second_child_ul',
            3 => 'dropdown third_child_ul',
            4 => 'dropdown fourth_child_ul', 
            5 => 'dropdown fifth_child_ul', 
            6 => 'dropdown sixth_child_ul', 
            7 => 'dropdown seventh_child_ul', 
        ];

        $ulClass = $levelClasses[$depth] ?? 'child-level-' . $depth;

        if ($depth == 0) {
            $html = '<ul id="siteNav" class="site-nav medium center hidearrow ' . $ulClass . '">';
        } else {
            $html = '<ul class="' . $ulClass . '">';
        }

        foreach ($items as $item) {
            $tag = '';
            if (!empty($item['tag_name']) && !empty($item['tag_color'])) {
                $tag = ' <span class="lbl nm_label2" style="background-color: ' . $item['tag_color'] . ' !important; text-transform: uppercase;">' . $item['tag_name'] . '</span>';
            }

            // Check if the current item has children
            $hasChildren = !empty($item['children']);
            
            // Add an additional class to the parent <li> if it has children
            $liClass = ' class="li_tag';
            if ($depth == 0) {
                $liClass .= ' lvl1 parent dropdown ' . $item['css_class'];
            }
            if ($hasChildren) {
                $liClass .= 'has_child_ulli'; // Add the class if the item has children
            }
            $liClass .= '"';
            
            $html .= '<li' . $liClass . '>';

            // Start the <a> tag and check if it has children to add the icon
            $html .= '<a class="a_tag" href="' . $item['href'] . '" target="' . $item['target'] . '">';
            $html .= $item['text'] . $tag;

            // If the item has children, add the angle-right icon
            if ($hasChildren) {
                $html .= ' <i class="anm anm-angle-right-l"></i>';
            }

            $html .= '</a>';

            // Recursively build the children menu
            if ($hasChildren) {
                $html .= buildMenu_Desk($item['children'], $depth + 1);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}

// menu html for desktop
if(!function_exists('buildMenu_Mobi')) {
    function buildMenu_Mobi(array $items, int $depth = 0): string
    {
        $levelClasses = [
            0 => 'first_ulm',
            1 => 'first_child_ulm',
            2 => 'second_child_ulm',
            3 => 'third_child_ulm',
            4 => 'fourth_child_ulm', 
            5 => 'fifth_child_ulm', 
            6 => 'sixth_child_ulm', 
            7 => 'seventh_child_ulm', 
        ];

        $ulClass = $levelClasses[$depth] ?? 'child-level-' . $depth;

        if ($depth == 0) {
            $html = '<ul id="MobileNav" class="mobile-nav ' . $ulClass . '">';
        } else {
            $html = '<ul class="' . $ulClass . '">';
        }

        foreach ($items as $item) {
            $tag = '';
            if (!empty($item['tag_name']) && !empty($item['tag_color'])) {
                $tag = ' <span class="lbl nm_label2" style="background-color: ' . $item['tag_color'] . ' !important; text-transform: uppercase;">' . $item['tag_name'] . '</span>';
            }

            $hasChildren = !empty($item['children']);
            $liClass = ' class="li_tag';
            if ($depth == 0) {
                $liClass .= ' lvl1 parent megamenu ' . $item['css_class'];
            }
            if ($hasChildren) {
                $liClass .= ' has_child_ullim'; 
            }
            $liClass .= '"';
            
            $html .= '<li' . $liClass . '>';
            $html .= '<a class="a_tag" href="' . $item['href'] . '" target="' . $item['target'] . '">';
            $html .= $item['text'] . $tag;
            if ($hasChildren) {
                $html .= ' <i class="anm anm-plus-l"></i>';
            }
            $html .= '</a>';

            if ($hasChildren) {
                $html .= buildMenu_Mobi($item['children'], $depth + 1);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';
        return $html;
    }
}

//remove 0 and null and make array asc order
if(!function_exists('beautifyarr')) {
    function beautifyarr($raw) {
        $array = array_filter($raw, function($value) {
            return $value !== 0 && $value !== null;  // Filters out 0 and null values
        });

        $array = array_unique($array); // Remove duplicate values
        $array = array_values($array); // Reindex the array to remove any gaps in the keys (optional)
        sort($array);

        return $array;
    }
}



//find min and max from given record db
if(!function_exists('findminmax')) {
    function findminmax($raw) {
        $price_range = [];
        foreach($raw as $prange){
            $price_range[] = $prange->min_simple_price != "" ? $prange->min_simple_price : $prange->min_variant_price;
            $price_range[] = $prange->max_simple_price != "" ? $prange->max_simple_price : $prange->max_variant_price;
        }
        //
        $price_range = array_unique($price_range);
        sort($price_range);
        return [$price_range[0], end($price_range)];
    }
}

//find range as per db record
if(!function_exists('make_range')) {
    function make_range($raw) {
        $new_arrx = [];
        foreach($raw as $key => $rangex){
            $new_arrx[] = $rangex->value;
        }
        return array_unique($new_arrx);
    }
}

