<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.login');
    }
    
    public function register()
    {
        return view('frontend.register');
    }  

    public function post_login(Request $request)
    {
        $customMessages = [
            'customer_email.required'  => 'Email field is required',
            'customer_password.required' => 'Password field is required'
        ];
        $request->validate([
            'customer_email' => 'required|email',
            'customer_password' => 'required'
        ], $customMessages);

        //echo $request->customer_email;

        //dd($request->all());
        
        //if (Auth::attempt(['email' => $request->customer_email, 'password' => $request->customer_password], $remember)) {
        if(Auth::attempt(['email' => $request->customer_email, 'password' => $request->customer_password])) {
            echo "11113333";
            return redirect()->route('home')->withSuccess('Welcome '. Auth::user()->name);
        } else {
            echo "2223333";
            //return back()->withError('Oppes! You have entered invalid credentials');
            return back()->withErrors(['login' => 'Oppes! You have entered invalid credentials']);
        }
        
        //return redirect("login")->withError('Oppes! You have entered invalid credentials');
        //return Response(['status' => 'error', 'message' => 'Oppes! You have entered invalid credentials']);
        
    } 

    public function post_register(Request $request)
    {
        $customMessages = [
            'c_first_name.required'  => 'First Name field is required',
            'c_last_name.required' => 'Last Name field is required',
            'c_email.required'  => 'Email field is required',
            'c_email.email'  => 'Email field is required valid email id',
            'c_password.required' => 'Password field is required',
            'c_password.min'  => 'Password length should be at least 6 characters',
        ];
        $request->validate([
            'c_first_name' => 'required|max:100',
            'c_last_name' => 'required|max:100',
            'c_email' => 'required|max:100|email|unique:users,email',
            'c_password' => 'required|max:100|min:6',
        ], $customMessages);

        $users = new User();
        $users->name = $request->c_first_name.' '.$request->c_last_name;
        $users->email = $request->c_email;
        $users->password = Hash::make($request->c_password);
        $users->role = 'user';
        $users->save();

        Auth::login($users);
        flash()->success('Welcome '.$request->c_first_name);
        return redirect()->route('home');

        //toastr()->success('Register successfully!');
        //flash()->success('Register successfully.');
    } 

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('home');
    }

    public function subscribe(Request $request){
        $count = DB::table('subscriber')->where('email', $request->email)->count();
        if ($count > 0) {
            return Response(['status' => 'fail', 'message' => 'Email already exists']);
        } else {
            DB::table('subscriber')->insert(['email' => $request->email]);
            return Response(['status' => 'success', 'message' => 'Email saved successfully']);
        }
    }

    public function forgotpassword(Request $request){
        $user = User::where('email', $request->email)->where('role', '!=', 'admin')->first();
        if ($user) {
            $token = Str::random(60);
            //
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);
            //
            echo $resetLink = url('password/reset/'.$token.'?email='.urlencode($request->email));
            //http://estore.test/password/reset/1ipnsHVWAF7v8NFW7TEjNqcT0iPfkUDtmtunG0bulBoGHDXpXu2pUcYj0gva?email=aa%40yy.in
            //Mail::to($request->email)->send(new ResetPasswordMail($resetLink));
        
            return Response(['status' => 'success', 'message' => 'Reset password mail send to your mail id']);
        } else {
            return Response(['status' => 'fail', 'message' => 'Email not found']);
        }
    }

    public function showResetForm($token, Request $request){
        $email = $request->query('email');
        $resetRecord = DB::table('password_reset_tokens')->where('token', $token)->where('email', $email)->first();
        if(!$resetRecord) {
            return redirect()->route('login')->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }
        return view('frontend.forgotpassword', ['token' => $token, 'email' => $email]);
    }

    public function savepassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',  // password confirmation rule
        ]);

        // Check if the token exists in the password_resets table
        $resetRecord = DB::table('password_reset_tokens')->where('token', $request->token)->where('email', $request->email)->first();
        if(!$resetRecord){ return back()->withErrors(['token' => 'This password reset token is invalid.']); }

        // Optionally, check if the token has expired (e.g., 60 minutes expiration)
        $createdAt = $resetRecord->created_at;
        $expiration = 60;  // Expiry time in minutes
        if(now()->diffInMinutes($createdAt) > $expiration) { return back()->withErrors(['token' => 'This password reset token has expired.']); }

        // Find the user by email
        $user = User::where('email', $request->email)->first();
        if(!$user){ return back()->withErrors(['email' => 'No user found with this email address.']); }
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token from the password_resets table to prevent reuse
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Redirect user to the login page with a success message
        return redirect()->route('login')->with('status', 'Your password has been successfully reset. You can now log in.');
        //
        //flash()->Success('Your password has been successfully reset. You can now log in.');
        //return redirect()->route('login');   
    }


}
