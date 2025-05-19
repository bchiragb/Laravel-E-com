<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsrOrderController extends Controller
{
    public function allorder(){
        return view('frontend.order.index');
    }

    public function showorder(Request $request){
        $id = $request->id;
        $order = DB::table('orders')->where('order_id', $id)->first();
        $ship = DB::table('ship')->where('order_id', $id)->first();
        if(Auth::user()->id !== $order->customer_id) {
            return redirect('/'); 
        }
        if($order->order_status == 4) {
            $can_ref_tbl = DB::table('order_cancel_refund')->where('order_id', $id)->first();
        } else {
            $can_ref_tbl = '';
        }

        return view('frontend.order.show', compact('id', 'order', 'ship', 'can_ref_tbl'));
    }

    // 
    public function allship(){
        $ship = DB::table('ship')
            ->join('orders', 'ship.order_id', '=', 'orders.order_id')
            ->select('ship.order_id', 'ship.sdate', 'ship.sname', 'ship.surl', 'ship.sno', 'ship.status')
            ->get();
        return view('frontend.ship', compact('ship'));
    }

    //
    public function editmyprofile(){
        return view('frontend.userchngprofile');
    }
    
    public function saveprofile(Request $request){
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'contact' => ['required']
        ]);

        $usr = User::findorfail(Auth::id());
        $usr->name = $request->first_name.' '.$request->last_name;
        $usr->contact = $request->contact;
        $usr->save();

        flash()->success('Profile Updated successfully');
        return redirect()->route('dashboard');  
    }

    //
    public function changepassword(){
        return view('frontend.userpasschange');
    }

    public function savepass(Request $request){
        $request->validate([
            'current_password' => 'required|string|current_password',
            'new_password' => 'required|min:6|string'
        ]);
        $auth = Auth::user();
	    if (!Hash::check($request->get('current_password'), $auth->password)){
            return back()->with('error', "Current Password is Invalid");
        }
        if (strcmp($request->get('current_password'), $request->new_password) == 0){
            return back()->with("error", "New Password cannot be same as your current password.");
        }
 
        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        flash()->success('Password updated successfully');
        return redirect()->route('dashboard');
    }

    //
    public function alladdress(){ 
        $bill_add = DB::table('address')->where('user_id', Auth::id())->where('add_type', 1)->where('insby', 1)->get();
        $ship_add = DB::table('address')->where('user_id', Auth::id())->where('add_type', 2)->where('insby', 1)->get();
        return view('frontend.address.index', compact('bill_add', 'ship_add'));
    }

    public function add_address(Request $request){
        $country = DB::table('countries')->select('id', 'name')->get();
        return view('frontend.address.create', compact('country'));
    }

    public function save_address(Request $request){
        $request->validate([
            'uniquename' => 'required|unique:address,uniquename',
            'add_type' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city' => 'required',
            'zipcode' => 'required'
        ]);

        $insdata = [
            'user_id' => Auth::id(),
            'uniquename' => $request->uniquename,
            'add_type' => $request->add_type,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'insby' => '1'
        ];
        
        $addressCount = DB::table('addresses')->where('user_id', Auth::id())->where('add_type', $request->add_type)->count();
        if($addressCount > 3) {
            return redirect()->back()->withErrors(['add_type' => 'You cannot have more than 3 addresses of this type.']);
            //->withInput() // Retain the old input
        } else {
            DB::table('address')->insert($insdata);
        }

        flash()->success('saved successfully');
        return redirect()->route('address');  
    }

    public function showadderss(Request $request, string $id)
    {
        $addressx = DB::table('address')->where('id', $id)->first();
        $country = DB::table('countries')->select('id', 'name')->get();
        $state = DB::table('states')->where('id', $addressx->state_id)->first();
        return view('frontend.address.edit', compact('country', 'state', 'addressx'));
    }

    public function editaddress(Request $request, string $id)
    {
        $request->validate([
            'uniquename' => 'required|unique:address,uniquename',
            'firstname' => 'required',
            'lastname' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'country_id' => 'required',
            'state_id' => 'required|gt:0',
            'city' => 'required',
            'zipcode' => 'required'
        ]);

        $insdata = [
            'uniquename' => $request->uniquename,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'insby' => '1'
        ];
        DB::table('address')->where('id', $id)->update($insdata);

        flash()->success('updated successfully');
        return redirect()->route('address');  
    }

    public function deleteaddress(Request $request)
    {
        DB::table('address')->where('id', $request->id)->delete();
        return redirect()->route('address');  
    }

    public function getaddress(Request $request){
        $addressx = DB::table('address')->where('id', $request->idx)->first();
        $statex = DB::table('states')->where('id', $addressx->state_id)->first();
        $response = [ 'addressx' => $addressx, 'statex' => ['id' => $statex->id, 'name' => $statex->name]];
        return response()->json($response); 
    }

    public function cancelorder(Request $request){
        DB::table('order_cancel_refund')->insert([
            'order_id' => $request->orderno,
            'cancelby' => 1,
            'cancelnote' => $request->cancel_reason,
            'refundamt' => 0,
            'refundnote' => '',
        ]);
        DB::table('orders')->where('order_id', $request->orderno)->update(['order_status' => $request->order_sts]);
        flash()->success('Your order Cancelled Successfully');
        return redirect()->back();  
    }
}
