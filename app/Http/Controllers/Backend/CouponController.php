<?php

namespace App\Http\Controllers\backend;

use App\DataTables\CouponDataTable;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CouponDataTable $tbl)
    {
        return $tbl->render('backend.coupon.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'max:2048'],
            'amt' => ['required'],
            'status' => ['required'],
        ]); 

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->desc = $request->desc;
        $coupon->qty = $request->qty;
        $coupon->type = $request->type;
        $coupon->amt = $request->amt;
        $coupon->limit = $request->limit;
        $coupon->stdt = $request->stdt;
        $coupon->eddt = $request->eddt;
        $coupon->status = $request->status;
        $coupon->save();

        flash()->Success('created sucessfully');
        return redirect()->route('admin.coupon.index');
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
        $coupon = Coupon::findorfail($id);
        return view('backend.coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code' => ['required', 'max:2048'],
            'amt' => ['required'],
            'status' => ['required'],
        ]); 

        $coupon = Coupon::findorfail($id);
        $coupon->code = $request->code;
        $coupon->desc = $request->desc;
        $coupon->qty = $request->qty;
        $coupon->type = $request->type;
        $coupon->amt = $request->amt;
        $coupon->limit = $request->limit;
        $coupon->stdt = $request->stdt;
        $coupon->eddt = $request->eddt;
        $coupon->status = $request->status;
        $coupon->save();

        flash()->Success('updated sucessfully');
        return redirect()->route('admin.coupon.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status == 'true' ? 1 : 0;
        $coupon->save();
        return response(["message" => 'status updated']);
    }
}
