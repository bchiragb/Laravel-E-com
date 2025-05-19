<?php

namespace App\Http\Controllers\backend;

use App\DataTables\ProductReviewDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductReviewDataTable $tbl, Request $request)
    {
        
        if ($request->has('product') && !empty($request->product)) { 
            $review = ProductReview::where('product_id', $request->product)->get();
        } else {  
            $review = ProductReview::all();
        }
        if ($request->ajax()) {
            return DataTables::of($review)
            ->addColumn('date', function($review) {
                return $review->pdate; 
            })
            ->addColumn('product', function($query){
                return $query->productnm->title;
                //return $query->product_id;
            })
            ->addColumn('status', function($query){
                if($query->status == 1) {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-val="'.$query->id.'" class="custom-switch-input chg_sts">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" data-val="'.$query->id.'" class="custom-switch-input chg_sts">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
                return $button;
            })
            ->addColumn('action', function($query){
                $editx = "<a href='".route('admin.review.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deletex = "<a href='".route('admin.review.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                return $editx.$deletex;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        }
        return $tbl->render('backend.product-review.index', compact('review'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();
        return view('backend.product-review.create', compact('product'));
    }

    public function create2()
    {
        $product = Product::all();
        //return view('backend.product-review.create2', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => ['required', 'string', 'max:250'],
            "star" => ['required', 'integer'],
            "review" => ['required', 'string', 'max:500'],
            "proid" => ['required', 'integer'],
            "datex" => ['required'],
            "status" => ['required'],
            "name" => ['required'],
            "email" => ['required'],
        ]);

        $review = new ProductReview();
        $review->product_id = $request->proid;
        $review->title = $request->title;
        $review->desc = $request->review;
        $review->star = $request->star;
        $review->pdate = $request->datex;
        $review->status = $request->status;
        $review->name = $request->name;
        $review->email = $request->email;
        $review->save();

        flash()->success('created successfully');
        return redirect()->route('admin.review.index');
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
        $review = ProductReview::findorfail($id);
        $product = Product::all();
        return view('backend.product-review.edit', compact('review', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "title" => ['required', 'string', 'max:250'],
            "star" => ['required', 'integer'],
            "review" => ['required', 'string', 'max:500'],
            "proid" => ['required', 'integer'],
            "datex" => ['required'],
            "status" => ['required'],
            "name" => ['required'],
            "email" => ['required'],
        ]);

        $review = ProductReview::findorfail($id);
        $review->product_id = $request->proid;
        $review->title = $request->title;
        $review->desc = $request->review;
        $review->star = $request->star;
        $review->pdate = $request->datex;
        $review->status = $request->status;
        $review->name = $request->name;
        $review->email = $request->email;
        $review->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.review.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

    /**
     * Change status when click on button.
     */
    public function chg_sts(Request $request){
        $review = ProductReview::findOrFail($request->id);
        $review->status = $request->status == 'true' ? 1 : 0;
        $review->save();
        return response(["message" => 'status updated']);
    }

    /**
     * save review form frontside
     */
    public function reviewsave(Request $request){
        $review = new ProductReview();
        $review->product_id = proidget($request->skuidxx);
        $review->star = $request->rating;
        $review->pdate = date('Y-m-d');
        $review->title = $request->review_title;
        $review->desc = $request->review_txt;
        $review->status = 0;
        $review->name = $request->review_name;
        $review->email = $request->review_email;
        $review->save();

        return Response(['status' => 'success', 'message' => 'Your review show after approval']);
    }
}
