<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductVariantDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use illuminate\support\Str;
use Illuminate\Support\Arr;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductVariantDataTable $tbl, Request $request)
    {
        $product = Product::findorfail($request->product);
        return $tbl->render('backend.product.variant.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $m_attr = Attribute::where('parent', '0')->where('status', '1')->orderby('id', 'asc')->get();
        $product = Product::findorfail($request->product);
        return view('backend.product.variant.create', compact('m_attr', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_product' => ['required'],
            'rprice' => ['required', 'integer'],
            'sprice' => ['integer'],
            'stock' => ['required', 'integer'],
            'sku' => ['required', 'string'],
        ]);
        
        $m_attr = Attribute::select('slug')->where('parent', '0')->where('status', '1')->orderby('id', 'asc')->get();
        // Convert the Eloquent collection to an array
        $dataArray = $m_attr->toArray();
        // Now use array_map to extract only the 'slug' values
        $slugArray = array_map(function ($item) {
            return $item['slug']; // Extract the 'slug' field
        }, $dataArray);

        //
        $attribute = new ProductVariant;
        $attribute->product_id = $request->parent_product;
        $attribute->rprice = $request->rprice;
        $attribute->sprice = $request->sprice;
        $attribute->stock = $request->stock;
        $attribute->sku = $request->sku;
        $attribute->status = $request->status;

        //setdata
        foreach($slugArray as $snm) { $attribute->$snm = $request->$snm; }
        $attribute->save();

        flash()->success('created successfully');
        return redirect()->route('admin.product-variant.index', ['product' => $request->parent_product]);
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
        $pro_attr = ProductVariant::findorfail($id);
        $m_attr = Attribute::where('parent', '0')->where('status', '1')->orderby('id', 'asc')->get();
        $product = Product::findorfail($pro_attr->product_id);
        return view('backend.product.variant.edit', compact('pro_attr', 'm_attr', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            //'parent_product' => ['required'],
            'rprice' => ['required', 'integer'],
            'sprice' => ['integer'],
            'stock' => ['required', 'integer'],
            'sku' => ['required', 'string'],
        ]);
        //
        $m_attr = Attribute::select('slug')->where('parent', '0')->where('status', '1')->orderby('id', 'asc')->get();
        $dataArray = $m_attr->toArray();
        
        $slugArray = array_map(function ($item) {
            return $item['slug'];
        }, $dataArray);
        //
        
        $ProductVariant = ProductVariant::findorfail($id);
        
        //$attribute->productid = $request->parent_product;
        $ProductVariant->rprice = $request->rprice;
        $ProductVariant->sprice = $request->sprice;
        $ProductVariant->stock = $request->stock;
        $ProductVariant->sku = $request->sku;
        $ProductVariant->status = $request->status;
        //setdata
        
        foreach($slugArray as $snm) { $ProductVariant->$snm = $request->$snm; }
        $ProductVariant->save();
        
        flash()->success('updated successfully');
        return redirect()->route('admin.product-variant.index', ['product' => $request->parent_product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ProductVariant = ProductVariant::findOrFail($id);
        $ProductVariant->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $ProductVariant = ProductVariant::findOrFail($request->id);
        $ProductVariant->status = $request->status == 'true' ? 1 : 0;
        $ProductVariant->save();
        return response(["message" => 'status updated']);
    }
}
