<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCateoryController extends Controller
{
    use ImageTraits;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductCategoryDataTable $tbl)
    {
        return $tbl->render('backend.product-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.product-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            //'desc' => ['string'],
            'parentcat' => ['required'],
            'status' => ['required'],
        ]);
        
        $imagepath = $this->UploadImage($request, 'image', 'uploads');
        $pcat = new ProductCategory();
        $pcat->img = $imagepath;
        $pcat->name = $request->name;
        $pcat->slug = Str::slug($request->name);
        $pcat->desc = $request->desc;
        $pcat->parent = $request->parentcat;
        $pcat->status = $request->status;
        $pcat->featured = $request->featured;
        $pcat->save();

        flash()->success('created successfully');
        return redirect()->route('admin.product-category.index');

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
        $categoryx = ProductCategory::findorfail($id);
        return view('backend.product-category.edit', compact('categoryx'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            //'desc' => ['string'],
            'parentcat' => ['required'],
            'status' => ['required'],
        ]);
        
        $pcat = ProductCategory::findorfail($id);
        $imgpath = $this->UpdateImage($request, 'image', 'uploads', $pcat->img);
        $pcat->img = empty(!$imgpath) ? $imgpath : $pcat->img;
        $pcat->name = $request->name;
        $pcat->slug = Str::slug($request->name);
        $pcat->desc = $request->desc;
        $pcat->parent = $request->parentcat;
        $pcat->status = $request->status;
        $pcat->featured = $request->featured;
        $pcat->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.product-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pcat = ProductCategory::findOrFail($id);

        $child_cate = ProductCategory::where('parent', $pcat->id)->count();
        if($child_cate > 0) {
            return Response(['status' => 'error', 'message' => 'Delete child category first.']);
        } else {
            //$this->deleteImage($pcat->img);
            $pcat->delete();
            return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
        }
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $pcat = ProductCategory::findOrFail($request->id);
        $pcat->status = $request->status == 'true' ? 1 : 0;
        $pcat->save();
        return response(["message" => 'status updated']);
    }

    public function shows(Request $request){
        //return "11111";  // return, not echo
        $procat = ProductCategory::whereIn('status', [0,1])->select('id', 'name', 'parent')->get()->toArray(); 
        
        foreach($procat as $row){                            
            $sub_data["id"] = $row["id"];
            $sub_data["name"] = $row["name"];
            $sub_data["text"] = $row["name"];
            $sub_data["parent"] = $row["parent"];
            $data[] = $sub_data;
        }       
        foreach($data as $key => &$value){
            $output[$value["id"]] = &$value;
        }
        foreach($data as $key => &$value){
            if($value["parent"] && isset($output[$value["parent"]])){
                $output[$value["parent"]]["nodes"][] = &$value;
            }
        }
        foreach($data as $key => &$value){
            if($value["parent"] && isset($output[$value["parent"]])){
                unset($data[$key]);
            }
        }
        $cate = json_encode($data);

        ////////////////////////////
        //$procat = ProductCategory::select('id', 'name', 'parent')->get();
        $procat = ProductCategory::leftJoin('products', 'product_categories.id', '=', 'products.category')
        ->select('product_categories.id', 'product_categories.name', 'product_categories.parent', \DB::raw('COUNT(products.id) as products_count'))
        ->groupBy('product_categories.id', 'product_categories.name', 'product_categories.parent')
        ->whereIn('product_categories.status', [0, 1])
        ->get();

        // Convert the data into the desired format for JavaScript
        $categories = $procat->map(function ($category) {
            if($category->parent == 0) { $p_cat = 0; } else { $p_cat = $category->parent; }
            return [
                'id' => $category->id,
                'name' => $category->name,
                'parent' => $p_cat,
                'tot' => $category->products_count,
            ];
        });

        $catz = $categories->toJson(); // Convert to JSON format for passing to JavaScript

        
        return view('backend.product-category.view', compact('catz'));
    }

}
