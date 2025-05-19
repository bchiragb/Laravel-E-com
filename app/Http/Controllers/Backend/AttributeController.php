<?php

namespace App\Http\Controllers\backend;

use App\DataTables\AttributeDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AttributeDataTable $tbl)
    {
        return $tbl->render('backend.attribute.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.attribute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'order' => ['integer'],
            'status' => ['required']
        ]);
        
        $attrnm = Str::slug($request->name);
        $attribute = new Attribute();
        $attribute->name = $request->name;
        $attribute->slug = $attrnm;
        $attribute->order = $request->order;
        $attribute->status = $request->status;
        $attribute->parent = 0;
        $attribute->value = $attrnm;
        $attribute->save();

        DB::statement('ALTER TABLE `product_variants` ADD COLUMN `'.$attrnm.'` INT(10) NULL DEFAULT NULL');

        flash()->success('created successfully');
        return redirect()->route('admin.attribute.index');
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
        $attribute = Attribute::findorfail($id);
        return view('backend.attribute.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'order' => ['integer'],
            'status' => ['required']
        ]);
        
        $attribute = Attribute::findorfail($id);
        $oldnm = $attribute->slug;
        $attrnm = Str::slug($request->name);

        DB::statement('ALTER TABLE `product_variants` CHANGE COLUMN `'.$oldnm.'` `'.$attrnm.'` INT(10) NULL DEFAULT NULL');

        $attribute->name = $request->name;
        $attribute->slug = $attrnm;
        $attribute->order = $request->order;
        $attribute->status = $request->status;
        $attribute->parent = 0;
        $attribute->value = $attrnm;
        $attribute->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.attribute.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        DB::statement('ALTER TABLE `product_variants` DROP COLUMN `'.$attribute->slug.'`');
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $attribute = Attribute::findOrFail($request->id);
        $attribute->status = $request->status == 'true' ? 1 : 0;
        $attribute->save();
        return response(["message" => 'status updated']);
    }
}
