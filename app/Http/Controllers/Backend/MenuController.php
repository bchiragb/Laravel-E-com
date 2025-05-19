<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    // start from footer menu
    public function footerindex(){
        $footmenu = SiteSetting::where('key', 'footermenu')->first();
        $footmenuset = SiteSetting::where('key', 'footermenuset')->first();
        $set1 = json_decode($footmenuset->val1, true);
        $set2 = json_decode($footmenuset->val2, true);
        $set3 = json_decode($footmenuset->val3, true);
        return view('backend.menu.footer', compact('footmenu', 'set1', 'set2', 'set3'));
    }

    public function savemenu(Request $request){
        SiteSetting::updateOrCreate(
            ['key' => 'footermenu'],
            ['val1' => $request->fmenu1, 'val2' => $request->fmenu2, 'val3' => $request->fmenu3, 'val4' => '', 'val5' => '']
        );
        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function savemenu1(Request $request){
        $request->validate([
            'fmenu11_nm' => ['required', 'max:2048'],
            'fmenu11_url' => ['required', 'URL'],
            'fmenu12_nm' => ['required', 'max:2048'],
            'fmenu12_url' => ['required', 'URL'],
            'fmenu13_nm' => ['required', 'max:2048'],
            'fmenu13_url' => ['required', 'URL'],
            'fmenu14_nm' => ['required', 'max:2048'],
            'fmenu14_url' => ['required', 'URL'],
        ]); 

        $sav_arr = [];
        $sav_arr[1] = array($request->fmenu11_nm, $request->fmenu11_url);
        $sav_arr[2] = array($request->fmenu12_nm, $request->fmenu12_url);
        $sav_arr[3] = array($request->fmenu13_nm, $request->fmenu13_url);
        $sav_arr[4] = array($request->fmenu14_nm, $request->fmenu14_url);
        $valx = json_encode($sav_arr);  
        //
        SiteSetting::updateOrCreate(
            ['key' => 'footermenuset'],
            ['val1' => $valx]
        );
        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function savemenu2(Request $request){
        $request->validate([
            'fmenu21_nm' => ['required', 'max:2048'],
            'fmenu21_url' => ['required', 'URL'],
            'fmenu22_nm' => ['required', 'max:2048'],
            'fmenu22_url' => ['required', 'URL'],
            'fmenu23_nm' => ['required', 'max:2048'],
            'fmenu23_url' => ['required', 'URL'],
            'fmenu24_nm' => ['required', 'max:2048'],
            'fmenu24_url' => ['required', 'URL'],
        ]); 

        $sav_arr = [];
        $sav_arr[1] = array($request->fmenu21_nm, $request->fmenu21_url);
        $sav_arr[2] = array($request->fmenu22_nm, $request->fmenu22_url);
        $sav_arr[3] = array($request->fmenu23_nm, $request->fmenu23_url);
        $sav_arr[4] = array($request->fmenu24_nm, $request->fmenu24_url);
        $valx = json_encode($sav_arr);  
        //
        SiteSetting::updateOrCreate(
            ['key' => 'footermenuset'],
            ['val2' => $valx]
        );
        flash()->success('Updated successfully');
        return redirect()->back();
    }

    public function savemenu3(Request $request){
        $request->validate([
            'fmenu31_nm' => ['required', 'max:2048'],
            'fmenu31_url' => ['required', 'URL'],
            'fmenu32_nm' => ['required', 'max:2048'],
            'fmenu32_url' => ['required', 'URL'],
            'fmenu33_nm' => ['required', 'max:2048'],
            'fmenu33_url' => ['required', 'URL'],
            'fmenu34_nm' => ['required', 'max:2048'],
            'fmenu34_url' => ['required', 'URL'],
        ]); 

        $sav_arr = [];
        $sav_arr[1] = array($request->fmenu31_nm, $request->fmenu31_url);
        $sav_arr[2] = array($request->fmenu32_nm, $request->fmenu32_url);
        $sav_arr[3] = array($request->fmenu33_nm, $request->fmenu33_url);
        $sav_arr[4] = array($request->fmenu34_nm, $request->fmenu34_url);
        $valx = json_encode($sav_arr);  
        //
        SiteSetting::updateOrCreate(
            ['key' => 'footermenuset'],
            ['val3' => $valx]
        );
        flash()->success('Updated successfully');
        return redirect()->back();
    }


    // start from header menu
    public function menuindex(){
        //$menux = DB::table('menu_items')->get();
        //{"text":"11","href":"http://estore.test/admin/menu/12","target":"_self","tag_name":"","tag_color":"","css_class":""},
        $menux = DB::table('menu_items')->select('id', 'parent',
            'name as text', 'link as href', 'link_type as target', 'tag_name', 'tag_color', 'cssclass as css_class', 'depth',
            DB::raw('CASE WHEN parent = 0 THEN 0 ELSE parent END as children')
        )->get()->toArray();
        $json = buildMenuTree($menux);
        ////
        //$rrr = getmenu();
        //echo $rrr;
        ////
        return view('backend.menu.menu', compact('json'));
    }

    
    public function savehmenu(Request $request){
        $menudatax = $request->data;
        $jsonx = json_decode($menudatax, true);
        $arrx = createArrayWithParent($jsonx);
        //print_r($arrx);
        //
        DB::table('menu_items')->truncate();
        foreach($arrx as $key => &$val){
            DB::table('menu_items')->insert([
                'name' => $val['text'],
                'link' => $val['href'],
                'link_type' => $val['target'],
                'tag_name' => $val['tag_name'],
                'tag_color' => $val['tag_color'],
                'cssclass' => $val['css_class'],
                'parent' => $val['parent'],
                'depth' => $val['depth'],
                'menu' => 1,
            ]);
        }
        //
        flash()->success('Menu saved successfully');
        //return redirect()->back();
        return Response(['status' => 'success', 'message' => 'Menu saved Successfully']);
    }

}
