<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderDataTable;
use App\DataTables\OrderViewDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderViewController extends Controller
{
    public function allorder(OrderDataTable $tbl){
        return $tbl->render('backend.order.index');
    }

    public function ordershow(String $id){
        $order = DB::table('orders')->where('order_id', $id)->first();
        $ship = DB::table('ship')->where('order_id', $id)->first();
        $user = User::findorfail($order->customer_id)->first();
        if($order->order_status == 4) {
            $can_ref_tbl = DB::table('order_cancel_refund')->where('order_id', $id)->first();
        } else {
            $can_ref_tbl = '';
        }
        return view('backend.order.order', compact('id', 'order', 'ship', 'user', 'can_ref_tbl'));
    }

    public function savenote(Request $request){
        $request->validate(['admin_note' => ['required']]);
        DB::table('orders')->where('order_id', $request->orderno)->update(['admin_note' => $request->admin_note]);
        flash()->success('Data saved successfully');
        return redirect()->route('admin.order.show', [$request->orderno]);
    }

    public function savests(Request $request){
        if($request->order_sts == 4) {
            $request->validate(['order_sts' => ['required'], 'cancel_reson' => ['required']]);
        } else {
            $request->validate(['order_sts' => ['required']]);
        }
        DB::table('orders')->where('order_id', $request->orderno)->update(['order_status' => $request->order_sts]);
        if($request->order_sts == 4) {
            DB::table('order_cancel_refund')->insert([
                'order_id' => $request->orderno,
                'cancelby' => 2,
                'cancelnote' => $request->cancel_reson,
                'refundamt' => 0,
                'refundnote' => '',
            ]);
        }
        //cb123 send mail pending -- cancel order, order status change        
        flash()->success('Order staus changed successfully');
        return redirect()->route('admin.order.show', [$request->orderno]);
    }

    public function saveshipping(Request $request){
        $request->validate(['ship_pro' => ['required'], 'ship_url' => ['required', 'url'], 'ship_code' => ['required']]);
        $shipdata = DB::table('ship')->where('order_id', $request->orderno)->get();
        $shipcount = $shipdata->count();
        if($shipcount < 0){
            $data = [
                'order_id' => $request->orderno,
                'sdate' => now(),
                'sname' => $request->ship_pro,
                'surl' => $request->ship_url,
                'sno' => $request->ship_code
            ];
            DB::table('ship')->insert($data);
        } else {
            DB::table('ship')->where('order_id', $request->orderno)->update(
                ['sdate' => now(), 'sname' => $request->ship_pro, 'surl' => $request->ship_url, 'sno' => $request->ship_code]
            );
        }
        
        //cb123 send mail pending
        flash()->success('Shipping details added successfully');
        return redirect()->route('admin.order.show', [$request->orderno]); 
    }

    public function deleteorder(String $id){
        DB::delete('DELETE FROM orders WHERE order_id = '.$id);
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

}
