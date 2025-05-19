<?php

namespace App\Http\Controllers\backend;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    use ImageTraits;
    
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('backend.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'name' => ['required', 'max:200'],
            'is_feature' => ['required'],
            'status' => ['required']
        ]);
        $logopath = $this->UploadImage($request, 'logo', 'uploads');

        $brand = new Brand();
        $brand->logo = $logopath;
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->is_featured = $request->is_feature;
        $brand->status = $request->status;
        $brand->save();

        flash()->success('created successfully');
        return redirect()->route('admin.brand.index');
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
        $brand = Brand::findorfail($id);
        return view('backend.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'logo' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'name' => ['required', 'max:200'],
            'is_feature' => ['required'],
            'status' => ['required'],
        ]); 

        $brand = Brand::findOrFail($id);
        $logopath = $this->UpdateImage($request, 'logo', 'uploads', $brand->logo);
        $brand->logo = empty(!$logopath) ? $logopath : $brand->logo;
        $brand->name = $request->name;
        $brand->slug = str::slug( $request->name);
        $brand->is_featured = $request->is_feature;
        $brand->status = $request->status;
        $brand->save();

        flash()->Success('update sucessfully');
        return redirect()->route('admin.brand.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $this->deleteImage($brand->logo);
        $brand->delete();
        //toastr()->Success('deleted sucessfully');
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $brand = Brand::findOrFail($request->id);
        $brand->status = $request->status == 'true' ? 1 : 0;
        $brand->save();
        return response(["message" => 'status updated']);
    }
}
