<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CodController extends Controller
{
    public function success(String $id){
        //  http://estore.test/payment/success/7094245826
        //
        $order_idx = DB::table('order')->where('admin_note', $id)->first();
        if(empty($order_idx)) { return redirect()->route('home'); }
        DB::table('order')->where('id', $order_idx->id)->update(['admin_note' => '', 'transaction_id' => $id, 'transacion_status' => 'complete']);
        //
        Cart::destroy();
        Session::forget('coupon');
        //
        //return redirect()->route('payment.success', [$order_idx->order_id], compact('id'));
        $ordid = $order_idx->order_id;
        return redirect()->route('payment.success', compact('ordid'));
    }

    public function success_show(Request $request, String $id){
        return view('frontend.payment.sucess', compact('id'));
    }

    public function cancel_show(Request $request, String $id){
        // echo "<pre>";
        // print_r($request->all);
        // print_r($id);
        return view('frontend.payment.cancel', compact('id'));
    }
}
