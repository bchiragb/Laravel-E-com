<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;

class RazorpayController extends Controller
{
    
    protected $api;

    public function __construct(){
        $config = $this->pay_config();
        $this->api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    
    
    
    
    public function pay_config(){
        $pay_setting1 = SiteSetting::where('key', 'razorpay_set1')->first();
        $pay_setting2 = SiteSetting::where('key', 'razorpay_set1')->first();
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
        //
        $config = [
            'id' => $cli_id,
            'se' => $cli_sec,
            'curr' => $currency
        ];
        return $config;
    }
    
    public function charge(Request $request){
        $config = $this->pay_config();
        $api = new Api($config['id'], $config['se']);
        // Generate an order on Razorpay
        $orderData = [
            'receipt'         => generateOrderId(),
            'amount'          => 100 * 1.1, //Cart::priceTotal(),
            'currency'        => 'INR',
            'payment_capture' => 1
        ];
        
        $order = $api->order->create($orderData); // Create order
        $orderId = $order->id; // Get order id

        Session::put('order_id', $orderId);

        return response()->json([
            'order_id' => $orderId,
            'key' => $config['id']
        ]);
    }
}
