<?php

namespace App\Http\Controllers\backend;

use App\DataTables\SocialDataTable;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SocialDataTable $datatable)
    {
        return $datatable->render('backend.social.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.social.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'icon' => ['required', 'string'],
            'title' => ['required', 'string'],
            'link' => ['required', 'string'],
            'status' => ['required']
        ]);

        $SiteSetting = new SiteSetting();
        $SiteSetting->key = 'Social';
        $SiteSetting->val1 = $request->title;
        $SiteSetting->val2 = $request->icon;
        $SiteSetting->val3 = $request->link;
        $SiteSetting->val5 = $request->status;
        $SiteSetting->save();

        flash()->Success('update sucessfully');
        return redirect()->route('admin.social.index');
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
        $social = SiteSetting::findorfail($id);
        return view('backend.social.edit', compact('social'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'icon' => ['required', 'string'],
            'title' => ['required', 'string'],
            'link' => ['required', 'string'],
            'status' => ['required']
        ]);

        $SiteSetting = SiteSetting::findorfail($id);
        $SiteSetting->val1 = $request->title;
        $SiteSetting->val2 = $request->icon;
        $SiteSetting->val3 = $request->link;
        $SiteSetting->val5 = $request->status;
        $SiteSetting->save();

        flash()->Success('update sucessfully');
        return redirect()->route('admin.social.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $SiteSetting = SiteSetting::findOrFail($id);
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
