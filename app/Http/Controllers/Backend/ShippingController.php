<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ShippingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ShippingDataTable $tbl)
    {
        return $tbl->render('backend.shipping.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $country = DB::table('countries')->select('id', 'name')->get();
        return view('backend.shipping.create', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'countryid' => ['required', 'integer'],
            'stateid' => ['required', 'integer'],
            'rate' => ['required', 'integer'],
            'status' => ['required', 'integer'],
        ]);

        $ship = new Shipping();
        $ship->countryid = $request->countryid;
        $ship->stateid = $request->stateid;
        $ship->rate = $request->rate;
        $ship->status = $request->status;
        $ship->save();

        flash()->success('created successfully');
        return redirect()->route('admin.shipping.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $country = DB::table('countries')->select('id', 'name')->get();
        $state = DB::table('states')->get();
        $shipping = Shipping::findorfail($id);
        return view('backend.shipping.edit', compact('country', 'state', 'shipping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'countryid' => ['required', 'integer'],
            'stateid' => ['required', 'integer'],
            'rate' => ['required', 'integer'],
            'status' => ['required', 'integer'],
        ]);

        $ship = Shipping::findorfail($id);
        $ship->countryid = $request->countryid;
        $ship->stateid = $request->stateid;
        $ship->rate = $request->rate;
        $ship->status = $request->status;
        $ship->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.shipping.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ship = Shipping::findOrFail($id);
        $ship->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $ship = Shipping::findOrFail($request->id);
        $ship->status = $request->status == 'true' ? 1 : 0;
        $ship->save();
        return response(["message" => 'status updated']);
    }

    /**
     * get state according country
     */
    public function getstate(Request $request)
    {
        $state = DB::table('states')->where('country_id',  $request->idx)->get();
        $statex = "";
        foreach($state as $stse){
            $statex .= '<option value="'.$stse->id.'">'.$stse->name.'</option>';
        }
        return $statex;
    }

    
}
