<?php

namespace App\Http\Controllers\backend;

use App\DataTables\SliderDataTable;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use ImageTraits;
    /**
     * Display a listing of the resource.
     */
    public function index(SliderDataTable $datatable)
    {
        return $datatable->render('backend.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['string'],
            'sub_title' => ['string'],
            'link' => ['string'],
            'image' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
            'status' => ['required']
        ]);

        $logopath = $this->UploadImage($request, 'image', 'uploads');

        $SiteSetting = new SiteSetting();
        $SiteSetting->key = 'slider';
        $SiteSetting->val1 = $logopath;
        $SiteSetting->val2 = $request->title;
        $SiteSetting->val3 = $request->sub_title;
        $SiteSetting->val4 = $request->link;
        $SiteSetting->val5 = $request->status;
        $SiteSetting->save();

        flash()->success('created successfully');
        return redirect()->route('admin.slider.index');
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
        $slider = Sitesetting::findorfail($id);
        return view('backend.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['string'],
            'sub_title' => ['string'],
            'link' => ['string'],
            'image' => ['image', 'max:2048', 'mimes:jpeg,png,jpg'],
            'status' => ['required']
        ]);

        $SiteSetting = SiteSetting::findOrFail($id);
        $logopath = $this->UpdateImage($request, 'image', 'uploads', $SiteSetting->val1);
        $SiteSetting->val1 = empty(!$logopath) ? $logopath : $SiteSetting->val1;
        $SiteSetting->val2 = $request->title;
        $SiteSetting->val3 = $request->sub_title;
        $SiteSetting->val4 = $request->link;
        $SiteSetting->val5 = $request->status;
        $SiteSetting->save();

        flash()->Success('update sucessfully');
        return redirect()->route('admin.slider.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $SiteSetting = SiteSetting::findOrFail($id);
        $this->deleteImage($SiteSetting->val1);
        $SiteSetting->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $SiteSetting = SiteSetting::findOrFail($request->id);
        $SiteSetting->val5 = $request->status == 'true' ? 1 : 0;
        $SiteSetting->save();
        return response(["message" => 'status updated']);
    }
}
