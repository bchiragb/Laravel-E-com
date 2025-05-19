<?php

namespace App\Http\Controllers\backend;

use App\DataTables\VariantDataTable;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VariantController extends Controller
{
    public function index(Request $request, VariantDataTable $tbl){
        $data = Attribute::where('id', $request->attr_id)->first();
        return $tbl->render('backend.variant.index', compact('data'));
    }

    public function create(string $attr_id){
        $pdata = Attribute::where('id', $attr_id)->first();
        return view('backend.variant.create', compact('pdata'));
    }

    public function store(Request $request){
        $request->validate([
            'parent' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'val' => ['required'],
            'order' => ['integer'],
            'status' => ['required']
        ]);

        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->value = $request->val;
        $attribute->parent = $request->parent;
        $attribute->order = $request->order;
        $attribute->status = $request->status;
        $attribute->save();

        flash()->success('created successfully');
        return redirect()->route('admin.variant.index', [$request->parent]);
    }

    public function edit(string $attr_id){
        $variant = Attribute::findorfail($attr_id);
        $pdata = Attribute::where('id', $variant->parent)->first();
        return view('backend.variant.edit', compact('variant', 'pdata'));
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'parent' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'val' => ['required'],
            'order' => ['integer'],
            'status' => ['required']
        ]);

        $attribute = Attribute::findorfail($id);
        $attribute->name = $request->name;
        $attribute->slug = Str::slug($request->name);
        $attribute->value = $request->val;
        $attribute->parent = $request->parent;
        $attribute->order = $request->order;
        $attribute->status = $request->status;
        $attribute->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.variant.index', [$request->parent]);  
    }

    public function destroy(string $id)
    {
        $Attribute = Attribute::findOrFail($id);
        $Attribute->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    public function chg_sts(Request $request){
        $Attribute = Attribute::findOrFail($request->id);
        $Attribute->status = $request->status == 'true' ? 1 : 0;
        $Attribute->save();
        return response(["message" => 'status updated']);
    }
}
