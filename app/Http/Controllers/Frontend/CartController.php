<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $currencyx;

    public function __construct(){
        $prodata = SiteSetting::where('key', 'productsetting2')->first();
        $this->currencyx = $prodata->val4;
    }
    
    public function cart(){
        $country = DB::table('countries')->select('id', 'name')->get();
        return view('frontend.cart', compact('country'));
    }
    
    public function addtocart(Request $request){
        $cart = [];
        $prodata = decode_proid_typ($request->pidx);
        $productid = $prodata[0];
        $producttype = $prodata[1];
        $qty = 0;
        if($request->has('qty')) {
            $qty = $request->qty;
        } else {
            $qty = 1;
        }
        //echo $productid; echo "=<br>";
        //echo $producttype; echo "=<br>";
        //echo $qty; echo "=<br>";
        //die();
        if($producttype == 1 && $qty > 0){
            $product = Product::findorfail($productid);
            $pricex = $product->sprice == 0 ? $product->rprice : $product->sprice;
            $cart['id'] = $productid;
            $cart['name'] = $product->title;
            $cart['qty'] = $qty;
            $cart['price'] = $pricex;
            $cart['weight'] = 0;
            $cart['options']['type'] = 1;
            $cart['options']['img'] = $product->img1;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['sku'] = $product->sku;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['idx'] = 0;
            $cart['options']['pricex'] = $pricex;
            //
            //$variation_data = ""; 
            //$p_img = $product->img1;
            //$name = $product->title;
            //$slug = $product->slug;
            //$qtyx = $request->quantity;
            //$price = $pricex;
        } elseif($producttype == 2 && $qty > 0) {
            $variant = ProductVariant::findorfail($productid);
            $pricex = $variant->sprice == 0 ? $variant->rprice : $variant->sprice;
            $product = Product::findorfail($variant->product_id);
            $cart['id'] = $variant->product_id;
            $cart['name'] = $product->title;
            $cart['qty'] = $qty;
            $cart['price'] = $pricex;
            $cart['weight'] = 0;
            $cart['options']['type'] = 2;
            $cart['options']['img'] = $product->img1;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['sku'] = $variant->sku;
            $cart['options']['idx'] = $variant->id;
            $cart['options']['pricex'] = $pricex;
            //
            //$variation_data = getvariationdata($productid, 0);
            //$p_img = $product->img1;
            //$name = $product->title;
            //$slug = $product->slug;
            //$qtyx = $request->quantity;
            //$price = $pricex;
        } else {
            return 1;
        }

        //print_r($cart);
        $cartItem = Cart::add($cart);
        $qty = Cart::content()->count();
        //
        $rowid = $cartItem->rowId;
        Cart::setTax($rowid, 0);
        $totamt = Cart::priceTotal();   
        // $htmlx = '
        // <li class="item pro_'.$rowid.'">
        //     <a class="product-image" href="#">
        //         <img src="'.asset($p_img).'" alt="'.$name.'" title="'.$name.'" />
        //     </a>
        //     <div class="product-details">
        //         <a href="#" class="remove rmove_itm" data="'.$rowid.'"><i class="anm anm-times-l" aria-hidden="true"></i></a>
        //         <a class="pName" href="'.route('product', $slug).'">'.$name.'</a>
        //         <div class="variant-cart">'.$variation_data.'</div>
        //         <div class="priceRow">
        //             <div class="product-price">
        //                 <span class="money pro_qty">Qty: '.$qtyx.'</span> |
        //                 <span class="money pro_pri">Price: '.$this->currencyx.$price.'</span> 
        //             </div>
        //           </div>
        //     </div>
        // </li>
        // ';

        $html = '';
        $html .= '<ul class="mini-products-list">';
        foreach(Cart::content() as $row) { $variation_data = "";
            if($row->options['type'] == 2) { //echo $productid;
                $variant = ProductVariant::findorfail($productid);
                //$variation_data = getvariationdata($productid, 0);
                $variation_data = getvariationdata($row->options['idx'], 0);
            } 
            $html .= '
            <li class="item pro_'.$row->rowId.'">
                <a class="product-image" href="'.route('product', $row->options['slug']).'">
                    <img src="'.asset($row->options['img']).'" alt="'.$row->name.'" title="'.$row->name.'" />
                </a>
                <div class="product-details">
                    <a href="javascript:;" class="remove rmove_itm" data="'.$row->rowId.'"><i class="anm anm-times-l" aria-hidden="true"></i></a>
                    <a class="pName" href="'.route('product', $row->options['slug']).'">'.$row->name.'</a>
                    <div class="variant-cart">'.$variation_data.'</div>
                    <div class="priceRow">
                        <div class="product-price">
                            <span class="money pro_qty">Qty: '.$row->qty.'</span> |
                            <span class="money pro_pri">Price: '.$this->currencyx.$row->price.'</span> 
                        </div>
                    </div>
                </div>
            </li>
            ';
        }
        $html .= '</ul>';

        $html .= '<div class="total ">
            <div class="total-in">
                <span class="label">Cart Subtotal:</span><span class="product-price"><span class="money">'.$this->currencyx.$totamt.'</span></span>
            </div>
            <div class="buttonSet text-center">
                <a href="'.route('cart').'" class="btn btn-secondary btn--small">View Cart</a>
                <a href="'.route('checkout').'" class="btn btn-secondary btn--small">Checkout</a>
            </div>
        </div>';


        return Response(['status' => 'success', 'message' => 'Product added in cart', 'qty' => $qty, 'html' => $html, 'totamt' => $totamt]);
    } 

    public function add_to_cart(Request $request){
        $cart = [];
        $prodata = decode_proid_typ($request->pidx);
        $productid = $prodata[0];
        $producttype = $prodata[1];
        $qty = 0;
        if($request->has('qty')) {
            $qty = $request->qty;
        } else {
            $qty = 1;
        }
        //
        if($producttype == 1 && $qty > 0){
            $product = Product::findorfail($productid);
            $pricex = $product->sprice == 0 ? $product->rprice : $product->sprice;
            $cart['id'] = $productid;
            $cart['name'] = $product->title;
            $cart['qty'] = $qty;
            $cart['price'] = $pricex;
            $cart['weight'] = 0;
            $cart['options']['type'] = 1;
            $cart['options']['img'] = $product->img1;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['sku'] = $product->sku;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['idx'] = 0;
            $cart['options']['pricex'] = $pricex;
            //
            $variation_data = ""; 
            $p_img = $product->img1;
            $name = $product->title;
            $slug = $product->slug;
            $qtyx = $qty;
            $price = $pricex;
        } elseif($producttype == 2 && $qty > 0) {
            $variant = ProductVariant::findorfail($productid);
            $pricex = $variant->sprice == 0 ? $variant->rprice : $variant->sprice;
            $product = Product::findorfail($variant->product_id);
            $cart['id'] = $variant->product_id;
            $cart['name'] = $product->title;
            $cart['qty'] = $qty;
            $cart['price'] = $pricex;
            $cart['weight'] = 0;
            $cart['options']['type'] = 2;
            $cart['options']['img'] = $product->img1;
            $cart['options']['slug'] = $product->slug;
            $cart['options']['sku'] = $variant->sku;
            $cart['options']['idx'] = $variant->id;
            $cart['options']['pricex'] = $pricex;
            //
            $variation_data = getvariationdata($productid, 0);
            $p_img = $product->img1;
            $name = $product->title;
            $slug = $product->slug;
            $qtyx = $qty;
            $price = $pricex;
        } else {
            return 1;
        }

        //print_r($cart);
        $cartItem = Cart::add($cart);
        $qty = Cart::content()->count();
        //
        $rowid = $cartItem->rowId;
        Cart::setTax($rowid, 0);
        $totamt = Cart::priceTotal();

        foreach(Cart::content() as $row) {
            $html = '
            <li class="item pro_'.$row->rowId.'">
                <a class="product-image" href="#">
                    <img src="'.asset($row->options['img']).'" alt="'.$row->name.'" title="'.$row->name.'" />
                </a>
                <div class="product-details">
                    <a href="#" class="remove rmove_itm" data="'.$row->rowId.'"><i class="anm anm-times-l" aria-hidden="true"></i></a>
                    <a class="pName" href="'.route('product', $slug).'">'.$row->name.'</a>
                    <div class="variant-cart">'.$variation_data.'</div>
                    <div class="priceRow">
                        <div class="product-price">
                            <span class="money pro_qty">Qty: '.$row->qty.'</span> |
                            <span class="money pro_pri">Price: '.$this->currencyx.$row->price.'</span> 
                        </div>
                    </div>
                </div>
            </li>
            ';
        }
        return Response(['status' => 'success', 'message' => 'Product added in cart', 'qty' => $qty, 'html' => $html, 'totamt' => $totamt]);
    }   

    public function cartdestroy(){
        Cart::destroy();
        return Response(['status' => 'success', 'message' => 'Cart is empty now']);
    }

    public function removeitem(Request $request){
        Cart::remove($request->pid);
        $qty = Cart::content()->count();
        $totamt = Cart::priceTotal();
        //
        $html = '';
        $html .= '<ul class="mini-products-list">';
        foreach(Cart::content() as $row) { $variation_data = "";
            if($row->options['type'] == 2) { //echo $productid;
                $variant = ProductVariant::findorfail($productid);
                //$variation_data = getvariationdata($productid, 0);
                $variation_data = getvariationdata($row->options['idx'], 0);
            } 
            $html .= '
            <li class="item pro_'.$row->rowId.'">
                <a class="product-image" href="'.route('product', $row->options['slug']).'">
                    <img src="'.asset($row->options['img']).'" alt="'.$row->name.'" title="'.$row->name.'" />
                </a>
                <div class="product-details">
                    <a href="javascript:;" class="remove rmove_itm" data="'.$row->rowId.'"><i class="anm anm-times-l" aria-hidden="true"></i></a>
                    <a class="pName" href="'.route('product', $row->options['slug']).'">'.$row->name.'</a>
                    <div class="variant-cart">'.$variation_data.'</div>
                    <div class="priceRow">
                        <div class="product-price">
                            <span class="money pro_qty">Qty: '.$row->qty.'</span> |
                            <span class="money pro_pri">Price: '.$this->currencyx.$row->price.'</span> 
                        </div>
                    </div>
                </div>
            </li>
            ';
        }
        $html .= '</ul>';

        $html .= '<div class="total ">
            <div class="total-in">
                <span class="label">Cart Subtotal:</span><span class="product-price"><span class="money">'.$this->currencyx.$totamt.'</span></span>
            </div>
            <div class="buttonSet text-center">
                <a href="'.route('cart').'" class="btn btn-secondary btn--small">View Cart</a>
                <a href="'.route('checkout').'" class="btn btn-secondary btn--small">Checkout</a>
            </div>
        </div>';
        
        return Response(['status' => 'success', 'message' => 'Product removed from cart', 'qty' => $qty, 'totamt' => $totamt, 'html' => $html]);
    }

    public function plusitem(Request $request){
        $amtx = 0;
        foreach(Cart::content() as $row) {
            if($row->rowId == $request->pid) { $amtx = $row->price; }
        }
        $amt = $amtx * $request->qty;
        Cart::update($request->pid, $request->qty);
        $totamt = Cart::priceTotal();
        return Response(['status' => 'success', 'message' => 'Product quantity changed', 'amt' => $amt, 'totamt' => $totamt]);
    }

    public function minusitem(Request $request){
        $amtx = 0;
        foreach(Cart::content() as $row) {
            if($row->rowId == $request->pid) { $amtx = $row->price; }
        }
        $amt = $amtx * $request->qty;
        Cart::update($request->pid, $request->qty);
        $totamt = Cart::priceTotal();
        return Response(['status' => 'success', 'message' => 'Product quantity changed', 'amt' => $amt, 'totamt' => $totamt]);
    }

    public function chkcoupon(Request $request){
        $coupon = Coupon::where('code', $request->codex)->where('status', '1')->first();
        if ($coupon) {
            //cb123
            // chk user coupon use & other validation
            if($coupon->stdt > date('Y-m-d') && $coupon->stdt != $coupon->eddt) {
                return Response(['status' => 'fail', 'message' => 'Coupon not found.', 'amt' => 0]);
            } elseif($coupon->eddt < date('Y-m-d') && $coupon->stdt != $coupon->eddt) {
                return Response(['status' => 'fail', 'message' => 'Coupon not found..', 'amt' => 0]);
            } else {
                if($coupon->type == 0){ //0-Amount
                    $totx = Cart::priceTotal();
                    $ftot = Cart::priceTotal() - $coupon->amt;
                    $discount = $coupon->amt;
                    $htmlcodex = $request->codex.' (S)';
                } else { //1-percentage  cb1011 (S)
                    $discount = (Cart::priceTotal() * $coupon->amt) / 100;
                    $ftot = Cart::priceTotal() - $discount;
                    $htmlcodex = $request->codex.' (%)';
                }
                Session::put('coupon', [
                    "code" => $request->codex,
                    "type" => $coupon->type,
                    "amt" => $coupon->amt,
                    "dis" => $discount,
                    "tot" => $ftot,
                ]);
                //Session::flush();
                return Response(['status' => 'success', 'message' => 'Coupon found & Applied', 'amt' => $coupon->amt, 'discount' => $discount, 'typ' => $coupon->type, 'htmlx' => $htmlcodex, 'ftot' => $ftot]);
            }
        } else {
            return Response(['status' => 'fail', 'message' => 'Coupon not found', 'amt' => 0]);
        }
    }

    public function rmovecoupon(){
        Session::forget('coupon');
        $ftot = Cart::priceTotal();
        return Response(['status' => 'success', 'message' => 'Coupon removed', 'ftot' => $ftot]);
    }

    public function shiprate(Request $request){
        //default value
        $def_data = DB::table('site_settings')->where('key', 'productsetting1')->first();
        $freeship_amt = $def_data->val1;
        $dom_rate = $def_data->val3;
        $int_rate = $def_data->val4;
        $store_country = $def_data->val5;
        $carttot = Cart::priceTotal();
        $ship_charge = 0;
        //chk ship country and store location is same or not
        if($store_country == $request->cid){
            $ship_charge = $dom_rate;
        } else {
            $ship_charge = $int_rate;
            $fullchk = DB::select('SELECT * FROM shippings WHERE countryid = '.$request->cid.' AND stateid = '.$request->sid);
            if($fullchk){ //echo "in db---";
                $ship_charge = $fullchk[0]->rate;
            } else { //echo "not in db--";
                $countrychk = DB::table('shippings')->where('countryid', $request->cid)->where('stateid', 0)->first();
                if($countrychk) { $ship_charge = $countrychk->rate; }
            }
        }

        //cart total big then ship decide amt
        if($freeship_amt != 0) { 
            if($freeship_amt < str_replace(',','',$carttot)){ 
                $ship_charge = 0;
            }
        }
        return $ship_charge;
    }
}
