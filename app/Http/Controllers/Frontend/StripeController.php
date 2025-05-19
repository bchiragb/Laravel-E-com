<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function pay_config(){
        $pay_setting1 = SiteSetting::where('key', 'stripe_set1')->first();
        $pay_setting2 = SiteSetting::where('key', 'stripe_set2')->first();
        //
        $currencyx = SiteSetting::where('key', 'productsetting2')->first();
        $currency = $currencyx->val3;
        $paymode = $pay_setting1->val1;
        if($paymode == 'sandbox') {
            $cli_id = $pay_setting1->val2;
            $cli_sec = $pay_setting1->val3;
        } else {
            $cli_id = $pay_setting2->val2;
            $cli_sec = $pay_setting2->val3;
        }
        
        $config = [
            'id' => $cli_id,
            'se' => $cli_sec,
            'curr' => $currency
        ];

        return $config;
    }
    
    public function payment(Request $request, String $id){
        echo "12222222--";
        echo $id; echo "<br>";
        // $config = $this->pay_config();
        // //
        // $stripe = new \Stripe\StripeClient($config['se']);
        // $response =  $stripe->checkout->sessions->create([
        //     //'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}', // Stripe will replace {CHECKOUT_SESSION_ID}
        //     'success_url' => route('stripe.success').'?trs='.$id,
        //     'cancel_url' => route('stripe.cancel'),
        //     'customer_email' => Auth::user()->email,
        //     'payment_method_types' => ['link', 'card'],
        //     'mode' => 'payment',
        //     'allow_promotion_codes' => true,
        //     'line_items' => [
        //         [
        //             'price_data'  => [
        //                 'product_data' => [
        //                     'name'     => 'Estore - Product',
        //                 ],
        //                 'unit_amount'  => 100 * 1.1, //Cart::priceTotal(),
        //                 'currency'     => $config['curr'],
        //             ],
        //             'quantity'         => 1 //Cart::content()->count()
        //         ],
        //     ]
        // ]);
        // return redirect($response['url']);
    }

    public function success(Request $request){
        $config = $this->pay_config();
        //
        $stripe = new \Stripe\StripeClient($config['se']);
        //$sessionx = $stripe->checkout->sessions->retrieve($request->session_id);
        $sessionx = $stripe->checkout->sessions->retrieve($request->trs);
        if ($sessionx->payment_status === 'paid') { // Payment was successful
            echo "paid sucessfully";
        } else {
            //return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        print_r($request->all()); //null coming - cancel all and remove all pro from cart
    }
}
