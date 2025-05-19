<?php

namespace App\Http\Controllers\backend;

use App\DataTables\PageDataTable;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Product;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PageDataTable $tbl)
    {   
        return $tbl->render('backend.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'status' => ['required']
        ]); 

        $page = new Page();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->content = $request->content;
        $page->status = $request->status;
        $page->save();
        //
        if($request->seo_cano == "") {
            $seo_cano = env('APP_URL').'/'.Str::slug($request->title);
        } else {
            $seo_cano = $request->seo_cano;
        }
        $seo = new Seo();
        $seo->url = '/'.Str::slug($request->title);
        $seo->type = 2;
        $seo->keyword = $request->seo_keyw;
        $seo->title = $request->seo_titl;
        $seo->desc = $request->seo_desc;
        $seo->canonical = $seo_cano;
        $seo->save();

        flash()->success('created successfully');
        return redirect()->route('admin.page.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = Page::findorfail($id);
        $seo = Seo::where('url', '/'.$page->slug)->first();
        return view('backend.page.edit', compact('page', 'seo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'status' => ['required']
        ]); 

        $page = Page::findorfail($id);
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->content = $request->content;
        $page->status = $request->status;
        $page->save();
        //
        if($request->seo_cano == "") {
            $seo_cano = env('APP_URL').'/'.Str::slug($request->title);
        } else {
            $seo_cano = $request->seo_cano;
        }
        $seo = new Seo();
        $seo->url = '/'.Str::slug($request->title);
        $seo->type = 2;
        $seo->keyword = $request->seo_keyw;
        $seo->title = $request->seo_titl;
        $seo->desc = $request->seo_desc;
        $seo->canonical = $seo_cano;
        $seo->save();

        flash()->success('created successfully');
        return redirect()->route('admin.page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $page = Page::findOrFail($request->id);
        $page->status = $request->status == 'true' ? 1 : 0;
        $page->save();
        return response(["message" => 'status updated']);
    }

    public function contactpage(){
        return view('frontend.contact');
    }

    public function contactmail(Request $request){
        //dd($request->all());
        // "name" => "test"
        // "email" => "22@yy.in"
        // "phone" => "55"
        // "subject" => "66"
        // "message" => "77"

        //cb123 send mail pending
        return Response(['status' => 'success', 'message' => 'Mail send successfully']);
    }

    public function productinqmail(Request $request){
        //dd($request->all());
        // "inq_product" => "11738820984"
        // "inq_name" => "11"
        // "inq_email" => "22@ee.in"
        // "inq_phone" => "33"
        // "inq_body" => "44"

        //cb123 send mail pending
        return Response(['status' => 'success', 'message' => 'Mail send successfully']);
    }

    public function searchproduct(Request $request) {
        $query = $request->input('q');
        //===========================
        $productsx = Product::where('title', 'like', '%'.$query.'%')
        ->orWhere('slug', 'like', '%'.$query.'%')
        ->orWhere('sku', 'like', '%'.$query.'%')
        ->get();

        $productsxx = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
                            ->select('products.id', 'products.title', 'products.slug', 'products.sku', 
                                        DB::raw('GROUP_CONCAT(product_variants.id) as variant_ids'))
                            ->where('products.title', 'like', '%'.$query.'%')
                            ->orWhere('products.slug', 'like', '%'.$query.'%')
                            ->orWhere('products.sku', 'like', '%'.$query.'%')
                            ->orWhere('product_variants.sku', 'like', '%'.$query.'%')
                            ->groupBy('products.id') // Group by product ID to avoid duplicates
                            ->get();
                    
        

        //===========================
        $productsx = Product::leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
                    //->distinct()
                    ->select('products.id as pid', 'product_variants.id as vid', 'products.img1', 'products.title', 'products.slug', 'products.sku as psku', 'product_variants.sku as vsku') 
                    ->where('products.title', 'like', '%'.$query.'%')
                    ->orWhere('products.slug', 'like', '%'.$query.'%')
                    ->orWhere('products.sku', 'like', '%'.$query.'%')
                    ->orWhere('product_variants.sku', 'like', '%'.$query.'%')
                    ->get();

        $product = [];
        foreach($productsx as $pro){ $product[] = array('pid' => $pro->pid, 'img1' => $pro->img1, 'title' => $pro->title, 'slug' => $pro->slug); }
        $products = array_map("unserialize", array_unique(array_map("serialize", $product)));


        return view('frontend.searchproduct', compact('query', 'products'));
    }


    public function faq() {
        $faqs = Faq::where('status', 1)->where('category', '!=', 0)->orderby('category', 'asc')->get();
        return view('frontend.faq', compact('faqs'));
    }
}
