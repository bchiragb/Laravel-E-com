<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\ProductCategory;
use App\Models\SiteSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class SiteSettingController extends Controller
{
    public function homesetting(){
        $data1 = SiteSetting::where('key', 'homesetting1')->first();
        $data2 = SiteSetting::where('key', 'sitelogo')->first();
        $info1 = SiteSetting::where('val1', 'infobox_1')->first();
        $info2 = SiteSetting::where('val1', 'infobox_2')->first();
        $info3 = SiteSetting::where('val1', 'infobox_3')->first();
        $info4 = SiteSetting::where('val1', 'infobox_4')->first();
        $home_s2 = SiteSetting::where('key', 'homesetting2')->first();
        $cate = ProductCategory::where('status', 1)->orderby('name', 'asc')->get();
        //
        $data11 = SiteSetting::where('key', 'productsetting1')->first();
        $data22 = SiteSetting::where('key', 'productsetting2')->first();
        $country = DB::table('countries')->select('id', 'name')->get();
        //
        $pay1 = SiteSetting::where('key', 'paypal_set1')->first();
        $pay2 = SiteSetting::where('key', 'paypal_set2')->first();
        //
        $stri1 = SiteSetting::where('key', 'stripe_set1')->first();
        $stri2 = SiteSetting::where('key', 'stripe_set2')->first();
        //
        $razor1 = SiteSetting::where('key', 'razorpay_set1')->first();
        $razor2 = SiteSetting::where('key', 'razorpay_set2')->first();
        //
        $paycod1 = SiteSetting::where('key', 'paycod_set1')->first();
        //
        $mailset1 = SiteSetting::where('key', 'mail_set1')->first();
        $mailset2 = SiteSetting::where('key', 'mail_set2')->first();
        //
        $popup = SiteSetting::where('key', 'popup')->first();
        //
        //$mailHost = Config::get('mail.mailers.smtp.host');
        //print_r($mailHost);
        //echo env('MAIL_HOST');
        //
        //Mail::raw('Test email body', function ($message) {
        //    $message->to('recipient@example.com')->subject('Test Email');
        //});

        return view('backend.sitesetting.homepage', compact('data1', 'data2', 'info1', 'info2', 'info3', 'info4', 'cate', 'home_s2', 'data11', 'data22', 'country', 'pay1', 'pay2', 'stri1', 'stri2', 'razor1', 'razor2', 'paycod1', 'mailset1', 'mailset2', 'popup'));
    }

    public function save_home_setting(Request $request){
        $request->validate([
            'contact_no' => ['required'],
            'email_txt' => ['required', 'email'],
            'address_txt' => ['required', 'string'],
            'center_txt' => ['string']
        ]);

        SiteSetting::updateOrCreate(
            ['key' => 'homesetting1'],
            ['val1' => $request->contact_no, 'val2' => $request->email_txt, 
            'val3' => $request->address_txt, 'val4' => $request->center_txt, 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_home_setting2(Request $request){
        $request->validate([
            'procat' => ['required'],
            'featu_pro_count' => ['required'],
            'newar_pro_count' => ['required'],
            'shop_product_count' => ['required'],
        ]);

        $catlist = implode(',', $request->procat);

        SiteSetting::updateOrCreate(
            ['key' => 'homesetting2'],
            ['val1' => $catlist, 'val2' => $request->featu_pro_count, 
            'val3' => $request->newar_pro_count, 'val4' => $request->shop_product_count, 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_logo_setting(Request $request){
        $request->validate([
            'logo_pic' => ['image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048']
        ]);

        $detailsx = SiteSetting::where('key', 'sitelogo')->first();
        $logopath = "";
       
        if($request->hasFile('logo_pic')){
            if(File::exists(public_path($detailsx->val1))){
                File::delete(public_path($detailsx->val1));
            }
            $image = $request->logo_pic;
            $imgnm = rand().'_'.$image->getClientOriginalName();   
            $image->move(public_path('uploads'), $imgnm);
            $path = "/uploads/".$imgnm;
            $logopath = $path;
        }
 
        SiteSetting::updateOrCreate(
            ['key' => 'sitelogo'],
            ['val1' => $logopath, 'val2' => '', 'val3' => '', 'val4' => '', 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_infobox(Request $request){
        $request->validate([
            'icon_1' => ['required'], 'title_1' => ['required'], 'sub_title_1' => ['required'],
            'icon_2' => ['required'], 'title_2' => ['required'], 'sub_title_2' => ['required'],
            'icon_3' => ['required'], 'title_3' => ['required'], 'sub_title_3' => ['required'],
        ]);

        SiteSetting::updateOrCreate(
            ['val1' => 'infobox_1'],
            ['key' => 'infobox', 'val2' => $request->icon_1, 'val3' => $request->title_1, 'val4' => $request->sub_title_1, 'val5' => '']
        );
        SiteSetting::updateOrCreate(
            ['val1' => 'infobox_2'],
            ['key' => 'infobox', 'val2' => $request->icon_2, 'val3' => $request->title_2, 'val4' => $request->sub_title_2, 'val5' => '']
        );
        SiteSetting::updateOrCreate(
            ['val1' => 'infobox_3'],
            ['key' => 'infobox', 'val2' => $request->icon_3, 'val3' => $request->title_3, 'val4' => $request->sub_title_3, 'val5' => '']
        );
        SiteSetting::updateOrCreate(
            ['val1' => 'infobox_4'],
            ['key' => 'infobox', 'val2' => $request->icon_4, 'val3' => $request->title_4, 'val4' => $request->sub_title_4, 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    
    public function productsetting(){
        $data1 = SiteSetting::where('key', 'productsetting1')->first();
        $data2 = SiteSetting::where('key', 'productsetting2')->first();
        $country = DB::table('countries')->select('id', 'name')->get();
        return view('backend.sitesetting.productsetting', compact('data1', 'data2', 'country'));
    }

    public function save_product_setting(Request $request){
        $request->validate([
            'ship_amt' => ['required'], 
            'ship_day' => ['required'], 
            'cur_sym' => ['required']
        ]);
        if($request->ship_amt == "") { $request->ship_amt = 0; }
        SiteSetting::updateOrCreate(
            ['key' => 'productsetting1'],
            ['val1' => $request->ship_amt, 'val2' => $request->ship_day, 'val3' => $request->dos_chrge, 'val4' => $request->inte_chrge, 'val5' =>  $request->ship_count]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'productsetting2'],
            ['val1' => $request->return_txt, 'val2' => $request->ship_txt, 'val3' => $request->cur_nm, 'val4' => $request->cur_sym, 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_paypal(Request $request){
        $request->validate([
            'paypal_sts' => ['required'], 
            'paypal_mod' => ['required'], 
            'sand_id' => ['required'],
            'sand_sec' => ['required'], 
            'sand_app' => ['required'], 
            'live_id' => ['required'],
            'live_sec' => ['required'], 
            'live_app' => ['required']
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'paypal_set1'],
            ['val1' => $request->paypal_mod, 'val2' => $request->sand_id, 'val3' => $request->sand_sec, 'val4' => $request->sand_app, 'val5' =>  $request->paypal_sts]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'paypal_set2'],
            ['val1' => $request->paypal_mod, 'val2' => $request->live_id, 'val3' => $request->live_sec, 'val4' => $request->live_app, 'val5' => $request->paypal_sts]
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_stripe(Request $request){
        $request->validate([
            'stripe_sts' => ['required'], 
            'stripe_mod' => ['required'], 
            'sand_key' => ['required'],
            'sand_sec' => ['required'], 
            'live_key' => ['required'],
            'live_sec' => ['required']
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'stripe_set1'],
            ['val1' => $request->stripe_mod, 'val2' => $request->sand_key, 'val3' => $request->sand_sec, 'val4' => '', 'val5' =>  $request->stripe_sts]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'stripe_set2'],
            ['val1' => $request->stripe_mod, 'val2' => $request->live_key, 'val3' => $request->live_sec, 'val4' => '', 'val5' => $request->stripe_sts]
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_razorpay(Request $request){
        $request->validate([
            'razor_sts' => ['required'], 
            'razor_mod' => ['required'], 
            'sand_key' => ['required'],
            'sand_sec' => ['required'], 
            'live_key' => ['required'],
            'live_sec' => ['required']
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'razorpay_set1'],
            ['val1' => $request->razor_mod, 'val2' => $request->sand_key, 'val3' => $request->sand_sec, 'val4' => '', 'val5' =>  $request->razor_sts]
        );
        SiteSetting::updateOrCreate(
            ['key' => 'razorpay_set2'],
            ['val1' => $request->razor_mod, 'val2' => $request->live_key, 'val3' => $request->live_sec, 'val4' => '', 'val5' => $request->razor_sts]
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }
    
    public function save_paycod(Request $request){
        $request->validate([
            'pay_note' => ['required'], 
            'cod_sts' => ['required'], 
            'cod_limit' => ['required']
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'paycod_set1'],
            ['val1' => $request->pay_note, 'val2' => $request->cod_limit, 'val3' => '', 'val4' => '', 'val5' =>  $request->cod_sts]
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function save_mailset(Request $request){
        $request->validate([
            'mail_host' => ['required'], 
            'mail_port' => ['required'], 
            'mail_user' => ['required'],
            'mail_pass' => ['required'], 
            'mail_encr' => ['required'], 
            'mail_name' => ['required'], 
            'mail_addr' => ['required']
        ]);
        SiteSetting::updateOrCreate(
            ['key' => 'mail_set1'],
            ['val1' => $request->mail_host, 'val2' => $request->mail_port, 'val3' => $request->mail_user, 'val4' => $request->mail_pass, 'val5' => '']
        );
        SiteSetting::updateOrCreate(
            ['key' => 'mail_set2'],
            ['val1' => $request->mail_encr, 'val2' => $request->mail_name, 'val3' => $request->mail_addr, 'val4' => $request->adminemail, 'val5' => '']
        );

        //overwrite mail data in env file
        $envFilePath = base_path('.env');
        if(File::exists($envFilePath)) {
            $envContent = File::get($envFilePath);
            $envContent = preg_replace('/^MAIL_HOST=.*/m', 'MAIL_HOST=' . $request->mail_host, $envContent);
            $envContent = preg_replace('/^MAIL_PORT=.*/m', 'MAIL_PORT=' . $request->mail_port, $envContent);
            $envContent = preg_replace('/^MAIL_USERNAME=.*/m', 'MAIL_USERNAME=' . $request->mail_user, $envContent);
            $envContent = preg_replace('/^MAIL_PASSWORD=.*/m', 'MAIL_PASSWORD=' . $request->mail_pass, $envContent);
            $envContent = preg_replace('/^MAIL_ENCRYPTION=.*/m', 'MAIL_ENCRYPTION=' . $request->mail_encr, $envContent);
            $envContent = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS="' . $request->mail_addr . '"', $envContent);
            $envContent = preg_replace('/^MAIL_FROM_NAME=.*/m', 'MAIL_FROM_NAME="' . $request->mail_name . '"', $envContent);
            //
            File::put($envFilePath, $envContent);
            // Optionally clear the configuration cache to reflect the changes
            //Artisan::call('config:clear');
        }
        // another way - 
        //putenv("MAIL_HOST=$request->mail_host");
        //config(['mail.mailers.smtp.host' => $request->mail_host]);
        
        flash()->success('Updated successfully');
        return redirect()->back();
    }


    public function save_popup(Request $request){
        $validatex = [];
        if($request->has('pop_img')) {
            $validate = ['pop_img' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048']];
        } 
        $validatex = [
            'pop_link' => ['required', 'url'],
            'pop_time' => ['required', 'integer', 'min:0', 'max:9']
        ];
        $request->validate($validatex);
        $detailsx = SiteSetting::where('key', 'popup')->first();
        $imgpath = "";
       
        if($request->hasFile('pop_img')){
            // if(File::exists(public_path($detailsx->val1))){
            //     File::delete(public_path($detailsx->val1));
            // }
            $image = $request->pop_img;
            $imgnm = rand().'_'.$image->getClientOriginalName();   
            $image->move(public_path('uploads'), $imgnm);
            $path = "/uploads/".$imgnm;
            $imgpath = $path;    
        } else {
            $imgpath = $detailsx->val1;
        }
 
        SiteSetting::updateOrCreate(
            ['key' => 'popup'],
            ['val1' => $imgpath, 'val2' => $request->pop_link, 'val3' => $request->pop_time, 'val4' => '', 'val5' => '']
        );

        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function sendtestmail(Request  $request){
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            //echo 'Valid email format';
            //
            $to = "chiragbaldaniya@gmail.com";
            $subject = "Test Email 5.13";
            $message = "This is a test email sent using PHP's mail() function in Laravel.";
            $headers = "From: devidb89@gmail.com";
            //
            // if(mail($to, $subject, $message, $headers)) {
            //     return 'Email sent successfully using PHP mail() function';
            // } else {
            //     return 'Failed to send email';
            // }


            try {
                // Attempt to send the email
                Mail::raw('This is a test email sent using SMTP.', function ($message) {
                    $message->to('chiragbaldaniya@gmail.com')
                            ->subject('Test Email from Laravel 5.38')
                            ->from('noreply@yourdomain.com');
                });
        
                // If mail is sent successfully, return a success message
                return 'Test email sent successfully!';

                Mail::send([], [], function ($message) {
                    $message->to('chiragbaldaniya@gmail.com')
                            ->subject('Test Email from Laravel 5.40')
                            ->from('noreply@yourdomain.com')
                            ->setBody('<h1>This is a test email</h1>', 'text/html'); // HTML email content
                });
        
            } catch (Exception $e) {
                // If there is an error, return the error message
                return 'Failed to send email. Error: ' . $e->getMessage();
            }


        } else {
            echo 'Invalid email format';
        }
    }

}
