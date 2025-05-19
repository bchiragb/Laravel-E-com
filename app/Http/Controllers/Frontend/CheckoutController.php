<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function checkout(){
        $country = DB::table('countries')->select('id', 'name')->get();
        //
        $paypal_sts = SiteSetting::where('key', 'paypal_set1')->select('val5')->first();
        $stripe_sts = SiteSetting::where('key', 'stripe_set1')->select('val5')->first();
        $razorpay_sts = SiteSetting::where('key', 'razorpay_set1')->select('val5')->first();
        $cod_sts = SiteSetting::where('key', 'paycod_set1')->select('val5', 'val1')->first();
        //
        $bill_add = DB::table('address')->where('user_id', Auth::id())->where('add_type', 1)->where('insby', 1)->get();
        $ship_add = DB::table('address')->where('user_id', Auth::id())->where('add_type', 2)->where('insby', 1)->get();
        //        
        return view('frontend.checkout', compact('country', 'paypal_sts', 'stripe_sts', 'razorpay_sts', 'cod_sts', 'bill_add', 'ship_add'));
    }

    public function checkout2(Request $request){
        //rdirect to home
        if(Cart::content()->isEmpty() && empty($request->all())) {
            return redirect()->route('home'); 
        }
        
        //total 4 tbl for order mgmt ( order, order_details, order_cancel_refund, ship for tracking)
        //echo "<pre>";
        //print_r($request->all());
        //return 101;

        //get, set userid
        if(Auth::check()) {  //if user login
            $user_id = Auth::id(); 
            $email = Auth::user()->email;
        } else {
            if(array_key_exists('crete_acc', $request->all())) {  //register user
                $users = new User();
                $users->name = $request->ship_firstname.' '.$request->ship_lastname;
                $users->email = $request->ship_email;
                $users->contact = $request->ship_contact;
                $passgen = generateOrderId();
                $users->password = Hash::make($passgen);
                $users->role = 'user';
                //$users->save();
                //Auth::login($users);
                //mail123 register with pass - $passgen  
                $user_id = Auth::id();
                $email = $request->ship_email;
            } else { //guest checkout
                $users = new User();
                $users->name = $request->ship_firstname.' '.$request->ship_lastname;
                $users->email = $request->ship_email;
                $users->contact = $request->ship_contact;
                $passgen = generateOrderId();
                $users->password = Hash::make($passgen);
                $users->role = 'guest';
                $users->save();
                $user_id = $users->id;
                //$user_id = 0;
                $email = $request->ship_email;
            }
        }

        //validation
        $validatearr = [
            'payment_method' => ['required'],
            'ship_contact' => ['required'],
            'ship_firstname' => ['required'],
            'ship_lastname' => ['required'],
            'ship_address_1' => ['required'],
            'ship_address_2' => ['required'],
            'ship_city' => ['required'],
            'ship_postcode' => ['required'],
            'ship_country' => ['required'],
            'ship_zone' => ['required'],
        ];
        if($request->bill_addr == 1){ 
            $validatearr['bill_firstname'] = ['required'];
            $validatearr['bill_lastname'] = ['required'];
            $validatearr['bill_address_1'] = ['required'];
            $validatearr['bill_address_2'] = ['required'];
            $validatearr['bill_city'] = ['required'];
            $validatearr['bill_postcode'] = ['required'];
            $validatearr['bill_country'] = ['required'];
            $validatearr['bill_zone'] = ['required'];   
        }
        $request->validate($validatearr);

        //save ship address
        $shipaddresssave = [
            'user_id' => $user_id,
            'uniquename' => '',
            'add_type' => 2, //1-bill, 2-ship
            'firstname' => $request->ship_firstname,
            'lastname' => $request->ship_lastname,
            'address_1' => $request->ship_address_1,
            'address_2' => $request->ship_address_2,
            'country_id' => $request->ship_country,
            'state_id' => $request->ship_zone,
            'city' => $request->ship_city,
            'zipcode' => $request->ship_postcode,
            'insby' => '2'  //1-manual, 2-order
        ];
        $ship_adr_id = DB::table('address')->insert($shipaddresssave);
        //$ship_adr_id = 1;
        //print_r($shipaddresssave); 
        
        //save bill address
        if($request->bill_addr != 0){ 
            $billaddresssave = [
                'user_id' => $user_id,
                'uniquename' => '',
                'add_type' => 1, //1-bill, 2-ship
                'firstname' => $request->bill_firstname,
                'lastname' => $request->bill_lastname,
                'address_1' => $request->bill_address_1,
                'address_2' => $request->bill_address_2,
                'country_id' => $request->bill_country,
                'state_id' => $request->bill_zone,
                'city' => $request->bill_city,
                'zipcode' => $request->bill_postcode,
                'insby' => '2'  //1-manual, 2-order
            ];
            $bill_adr_id = DB::table('address')->insert($billaddresssave);
            //$bill_adr_id = 1;
        } else { $bill_adr_id = 0; $billaddresssave = []; }

        //
        $contact = $request->ship_contact != "" ? $request->ship_contact : Auth::user()->contact;
        if(array_key_exists('ship_email', $request->all())) { $email = $request->ship_email; } //else { $email = $request->email; }
        $uniid = "";  $uniid = uniqid('txn_', true); $uniid = str_replace('.', '', $uniid); //$uniid = generateOrderId();
        $ordid = "";  $ordid = generateOrderId();

        //get discount amt
        if (Session::has('coupon')) {
            $dis_arr = Session::get('coupon');
            $dis_amt = $dis_arr['amt'];
        } else {
            $dis_amt = 0;
            $dis_arr = [];
        }

        //ship charge count pending
        $ship_amt = 0; $ship_arr = [];

        //raw data collection of send form, bill, ship, product, coupon, shipping
        $rawx = [];
        $rawx['form'] = $request->all();
        $rawx['ship'] = $shipaddresssave;
        $rawx['bill'] = $billaddresssave;
        $rawx['product'] = Cart::content();
        $rawx['coupon'] = $dis_arr;
        $rawx['shipping'] = $ship_arr;
        $raw = json_encode($rawx);

    
        //save data in order tbl
        $order_tbl_save = [
            'order_id' => $ordid,
            'customer_id' => $user_id,
            'order_status' => 1,
            'order_date' => date('Y-m-d H:i:s'),
            'payment_mode' => $request->payment_method,
            'transaction_id' => '0',
            'transacion_status' => 'pending',
            'total_amount' => (int) str_replace([',', '.00'], '', Cart::priceTotal()),
            'discount_amt' => $dis_amt,
            'shipping_charge' => $ship_amt,
            'ship_address' => $ship_adr_id,
            'bill_address' => $bill_adr_id,
            'email' => $email,
            'contact_no' => $contact,
            'order_note' => $request->customernote,
            'admin_note' => $uniid,
            'rawdata' => $raw,
        ];

        $order_tbl = DB::table('order')->insert($order_tbl_save);
        //print_r($order_tbl_save);

        //save product data
        $order_details = [];
        foreach(Cart::content() as $row) {
            $order_details[] = [
                'order_id' => $ordid,
                'product_id' => $row->id,
                'variant_id' => $row->options->idx,
                'price' => $row->price,
                'qty' => $row->qty,
                'type' => $row->options->type,
                'sku' => $row->options->sku
            ];
        }
        //print_r($order_details);
        $order_details = DB::table('order')->insert($order_tbl_save);

        //redirect to payment getway
        if($request->payment_method == 'pay-paypal') { return redirect()->route('paypal.payment', ['id' => $uniid]); }
        if($request->payment_method == 'pay-stripe') { return redirect()->route('stripe.payment', ['id' => $uniid]); }
        if($request->payment_method == 'pay-razorpay') { return redirect()->route('razorpay.payment', ['id' => $uniid]); }
        if($request->payment_method == 'pay-cod') { 
            //return redirect()->route('cod.success', ['id' => $uniid], 302); 

            return redirect()->route('cod.success', ['id' => $uniid]);
            //return redirect()->route('cod.success', ['id' => $uniid])->setStatusCode(302);
            //return redirect()->route('cod.success', ['id' => $uniid], 302); 
            //return redirect()->route('cod.success', int $status = 302, ['id' => $uniid]); 

            //return redirect()->route('cod.success', compact('uniid')); 
            //return redirect()->intended('cod/payment')->with('uniid', $uniid);
            //return redirect()->route('cod.success', ['id' => $uniid], 302); 
        
        }
    }
}
