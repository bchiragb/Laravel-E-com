<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductImgsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImgs;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;

class ProductImgsController extends Controller
{
    use ImageTraits;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductImgsDataTable $tbl, Request $request)
    {
        $product = Product::findorfail($request->product);
        return $tbl->render('backend.product.imgs.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image.*' => ['required', 'image', 'max:2048']
        ]);
        if($request->image == ""){
            flash()->warning("Select image which you like to upload");
            return redirect()->back();
        }

        $img_paths = $this->UploadMultiImage($request, 'image', 'uploads');
        foreach($img_paths as $path){
            $proimggal = new ProductImgs();
            $proimggal->image = $path;
            $proimggal->product_id = $request->product;
            $proimggal->save();
        }

        flash()->success("Image Uploaded sucessfully");
        return redirect()->back();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proimggall = ProductImgs::findOrfail($id);
        $this->deleteImage($proimggall->image);
        $proimggall->delete();
        return response(['status' => 'success',  'message' => 'Deleted Successfully']);
    }
}
