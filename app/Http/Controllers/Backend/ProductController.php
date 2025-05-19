<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Seo;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use illuminate\support\Str;

class ProductController extends Controller
{
    use ImageTraits;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $tbl)
    {
        return $tbl->render('backend.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brand = Brand::where('status', 1)->get();
        $cat = ProductCategory::where('status', 1)->get();
        return view('backend.product.create', compact('brand', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {           
        $request->validate([
            'img1' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'img2' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'title' => ['required', 'string'],
            'category' => ['required'],
            'desc' => ['required'],
            'ptype' => ['required'],
            //'video' => ['string', 'url'],
            //'sku' => ['required'],
            //'stock' => ['required']
            //'seo_keyw' => ['string'],
            //'seo_titl' => ['string'],
            //'seo_cano' => ['string', 'url'],
            //'seo_desc' => ['string'],
        ]);
        
        $img1 = $this->UploadImage($request, 'img1', 'uploads');
        $img2 = '';
        if($request->hasFile('img2')){
            $img2 = $this->UploadImage($request, 'img2', 'uploads');
        }

        if($request->ptype == 1){
            if($request->rprice == '' && $request->sprice == '' && $request->stock == ''){
                flash()->error('Data not fill properly');
                return redirect()->route('admin.product.index');
            }
        }

        // $stdtx = ''; $eddtx = ''; 
        // if($request->stdt != $request->eddt) {
        //     $stdtx = $request->stdt; $eddtx = $request->eddt;
        // }

        if($request->has('tag')) {
            $tags = implode(',',$request->tag);
        } else {
            $tags = '';
        }
        //
        $product = new Product();
        $product->img1 = $img1;
        $product->img2 = $img2;
        $product->video = $request->video;
        $product->title = $request->title;
        $product->slug = Str::slug($request->title);
        $product->sku = $request->sku;
        $product->category = $request->category;
        $product->brand = $request->brand;
        $product->tag = $tags;
        $product->stock = $request->stock;
        $product->desc = $request->desc;
        $product->info = $request->info;
        $product->ptype = $request->ptype;
        $product->rprice = $request->rprice;
        $product->sprice = $request->sprice;
        $product->stdt = $request->stdt;
        $product->eddt = $request->eddt;
        $product->sts = $request->status;
        $product->save();
        //
        if($request->seo_titl != "") {
            if($request->seo_cano == "") {
                $seo_cano = env('APP_URL').'/'.Str::slug($request->title);
            } else {
                $seo_cano = $request->seo_cano;
            }
            $seo = new Seo();
            $seo->url = '/product/'.Str::slug($request->title);
            $seo->type = 1;
            $seo->keyword = $request->seo_keyw;
            $seo->title = $request->seo_titl;
            $seo->desc = $request->seo_desc;
            $seo->canonical = $seo_cano;
            $seo->save();
        }
        
        flash()->success('created successfully');
        return redirect()->route('admin.product.index');
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
        $product = Product::findorfail($id);
        $brand = Brand::where('status', 1)->get();
        $cat = ProductCategory::where('status', 1)->get();
        $seo = Seo::where('url', '/product/'.$product->slug)->first();
        return view('backend.product.edit', compact('product', 'brand', 'cat', 'seo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'img1' => ['image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'img2' => ['image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'title' => ['required', 'string'],
            'category' => ['required'],
            'desc' => ['required'],
            'ptype' => ['required'],
            //'seo_keyw' => ['string'], //'required'
            //'seo_titl' => ['string'],
            //'seo_cano' => ['string', 'url'],
            //'seo_desc' => ['string'],
        ]);

        $product = Product::findorfail($id);
        $img1 = $this->UpdateImage($request, 'img1', 'uploads', $product->img1);
        $product->img1 = empty(!$img1) ? $img1 : $product->img1;
        $img2 = $this->UpdateImage($request, 'img2', 'uploads', $product->img2);
        $product->img2 = empty(!$img2) ? $img2 : $product->img2;
        
        if($request->ptype == 1){
            if($request->rprice == '' && $request->sprice == '' && $request->stock == ''){
                flash()->error('Data not fill properly');
                return redirect()->route('admin.product.index');
            }
        }
        if($request->has('tag')) {
            $tags = implode(',',$request->tag);
        } else {
            $tags = '';
        }
        //
        $product->video = $request->video;
        $product->title = $request->title;
        $product->slug = Str::slug($request->title);
        $product->sku = $request->sku;
        $product->category = $request->category;
        $product->brand = $request->brand;
        $product->tag = $tags;
        $product->stock = $request->stock;
        $product->desc = $request->desc;
        $product->info = $request->info;
        $product->ptype = $request->ptype;
        $product->rprice = $request->rprice;
        $product->sprice = $request->sprice;
        $product->stdt = $request->stdt;
        $product->eddt = $request->eddt;
        $product->sts = $request->status;
        $product->save();
        //
        if($request->seo_titl != "") {
            if($request->seo_cano == "") {
                $seo_cano = env('APP_URL').'/'.Str::slug($request->title);
            } else {
                $seo_cano = $request->seo_cano;
            }
            $seo = new Seo();
            $seo->url = '/product/'.Str::slug($request->title);  
            $seo->type = 1;
            $seo->keyword = $request->seo_keyw;
            $seo->title = $request->seo_titl;
            $seo->desc = $request->seo_desc;
            $seo->canonical = $seo_cano;
            $seo->save();
        }
        flash()->success('updated successfully');
        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Product = Product::findOrFail($id);
        // $child_cate = Product::where('parent', $Product->id)->count();
        // if($child_cate > 0) {
        //     return Response(['status' => 'error', 'message' => 'Delete child category first.']);
        // } else {
        //     $this->deleteImage($Product->img1);
        //     $this->deleteImage($Product->img2);
        //     $Product->delete();
        //     return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
        // }

        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $Product = Product::findOrFail($request->id);
        $Product->sts = $request->status == 'true' ? 1 : 0;
        $Product->save();
        return response(["message" => 'status updated']);
    }
}
