<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //$infodatax = SiteSetting::where('key', 'homesetting1')->first();
        //$social = SiteSetting::where('key', 'Social')->orderby('id', 'asc')->get();
        //$prodata = SiteSetting::where('key', 'productsetting2')->first();
        //$popupx = SiteSetting::where('key', 'popup')->first();
        //$fdata1 = SiteSetting::where('key', 'footermenu')->first();
        //$fdata2 = SiteSetting::where('key', 'footermenuset')->first();

        $keys = ['homesetting1', 'Social', 'productsetting2', 'popup', 'footermenu', 'footermenuset'];
        $settings = SiteSetting::whereIn('key', $keys)->orderBy('id', 'asc')->get()->groupBy('key');
        $social = $settings['Social'];
        $infodatax = $settings->get('homesetting1')->first();
        $prodata = $settings->get('productsetting2')->first();
        $popupx = $settings->get('popup')->first();
        $fdata1 = $settings->get('footermenu')->first();
        $fdata2 = $settings->get('footermenuset')->first();
        //
        $msg = greet('|-OM-|');
        //
        View::share('appName', config('app.name'));
        View::share('currency', $prodata->val4);
        View::composer('*', function($view) use ($infodatax, $social, $popupx, $fdata1, $fdata2, $msg){
            $view->with('infobox_s1', $infodatax)
                 ->with('popup', $popupx)
                 ->with('social_icon', $social)
                 ->with('msg', $msg)
                 ->with('foot_menu', $fdata1)
                 ->with('foot_item', $fdata2);
        });

        
        
        $mailHost = Config::get('mail.mailers.smtp.host');
        //print_r($mailHost);
        
        View::share('MAIL_HOST', env('MAIL_HOST'));
        View::share('MAIL_HOST1', $mailHost);

        // View::composer('your.view.name', function ($view) {
        //     $view->with('key', 'value');
        // });

    }
}
