<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    
    public function pay_config(){
        $pay_setting1 = SiteSetting::where('key', 'paypal_set1')->first();
        $pay_setting2 = SiteSetting::where('key', 'paypal_set2')->first();
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
            'mode'    => $paymode,
            $paymode => [
                'client_id'         => $cli_id,
                'client_secret'     => $cli_sec,
                'app_id'            => 'APP-80W284485P519543T',
            ],  
            'payment_action' => 'Sale',
            'currency'       => $currency,
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
        return $config;
    }
    
    public function payment(Request $request, string $id){
        echo "12222222--";
        echo $id; echo "<br>";
        // $config = $this->pay_config();
        // $provider = new PayPalClient($config);
        // $token = $provider->getAccessToken();
        // $provider->setAccessToken($token);
        // $response = $provider->createOrder([
        //     "intent" => "CAPTURE",
        //     "application_context" => [
        //         //"return_url" => route('paypal.success'),
        //         "return_url" => route('paypal.success') . '?trs=' . $id,
        //         "cancel_url" => route('paypal.cancel')
        //     ],
        //     "purchase_units" => [
        //         [
        //             "amount" => [
        //                 "currency_code" => $config['currency'],
        //                 "value" => "2.00"
        //             ]
        //         ]
        //     ]
        // ]);
        // //
        // if(isset($response['id']) && !empty($response['id'])) {  
        //     foreach($response['links'] as $link){
        //         if($link['rel'] == 'approve') {
        //             return redirect()->away($link['href']);
        //             exit();   
        //         } 
        //     }
        // } else {
        //     return redirect()->route('paypal.cancel');
        // }
    }

    public function success(Request $request){
        //echo $request->PayerID;
        $transactionId = $request->query('trs'); // Retrieve from the URL
        $config = $this->pay_config();
        $provider = new PayPalClient($config);
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);
        $response = $provider->capturePaymentOrder($request->token);
        //print_r($response);
        if(isset($response) && !empty($response['status']) && $response['status'] == 'COMPLETED') {  
            echo "paid sucessfully--".$transactionId.'--'.$request->PayerID;
        } else { echo "back";
            //return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        echo $request->token;
    }

}
