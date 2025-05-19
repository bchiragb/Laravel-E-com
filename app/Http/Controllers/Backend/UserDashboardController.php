<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserDashboardController extends Controller
{
    
    public function index(Request $request){
        $showbox = "1";
        if (!Auth::check() || Auth::user()->role !== 'admin') {            
            $showbox = "0";
        } 
        return view('backend.login', compact(['showbox']));
    }

    public function post_login_admin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard')->withSuccess('Welcome '. Auth::user()->name);
        }
        return redirect()->back()->withError('Oppes! You have entered invalid credentials');
    }

    public function dashboard(Request $request){
        $tot_user = DB::table('users')->count();
        $tot_product = DB::table('products')->count();
        $tot_order = DB::table('orders')->count();
        $tot_sell = DB::table('orders')->whereDate('order_date', today())->get();
        $tod_amt = $tot_sell->sum('total_amount');
        return view('backend.dashboard', compact('tot_user', 'tot_product', 'tot_order', 'tod_amt'));
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function profile() {
        //toastr()->success('Profile updated successfully xxx');
        //flash()->success('Profile updated successfully ccccccccc');
        return view('backend.profile');
    }

    public function changepassword(Request $request){
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
        return redirect()->route('admin.profile');
    }

    public function aeditprofile(Request $request){
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            //'mobile' => 'required|string|min:7',
            'profile_pic' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        if($request->hasFile('profile_pic')){
            if(File::exists(public_path($user->img))){
                File::delete(public_path($user->img));
            }
            $image = $request->profile_pic;
            $imgnm = rand().'_'.$image->getClientOriginalName();   
            $image->move(public_path('uploads'), $imgnm);
            $path = "/uploads/".$imgnm;
            $user->img = $path;
        }

        $user->name = $request->first_name.' '.$request->last_name;
        $user->contact = $request->mobile;
        $user->save();
        flash()->success('Profile updated successfully');
        return redirect()->back();
    }
}
