<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    use HasFactory;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productx = array(); $c = 1;
        if(Auth::check()) {
            $userId = Auth::id();
            $wishlist = Wishlist::where('user_id', $userId)->get();
            foreach($wishlist as $wi){
                $product = Product::select('title','slug','img1')->where('id', $wi->product_id)->first();
                $productx[$c]['id'] = $c;
                $productx[$c]['idx'] = proidhide($wi->product_id);
                $productx[$c]['img'] = $product->img1;
                $productx[$c]['title'] = $product->title;
                $productx[$c]['slug'] = $product->slug;
                $c++;    
            }
        } else {
            $wishlist = session()->get('wishlist', []);
            foreach($wishlist as $wi){
                $product = Product::select('title','slug','img1')->where('id', $wi)->first();
                $productx[$c]['id'] = $c;
                $productx[$c]['idx'] = proidhide($wi);
                $productx[$c]['img'] = $product->img1;
                $productx[$c]['title'] = $product->title;
                $productx[$c]['slug'] = $product->slug;
                $c++;
            }
        }

        //echo "<pre>"; print_r($productx);


        return view('frontend.wishlist', compact('productx'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $proid = decode_proid($request->idx);
        if(Auth::check()) {
            $userId = Auth::id();
            //$wishpro = Wishlist::where('user_id', $userId)->where('product_id', $proid)->get();
            $wishpro = DB::table('wishlists')->where('user_id', $userId)->where('product_id', $proid)->first();
            if($wishpro){
                return Response(['status' => 'warning', 'message' => 'Product already in your wishlist']);
            } else {
                DB::insert('INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)', [$userId, $proid]);
                return Response(['status' => 'success', 'message' => 'Product added to your wishlist']);
            }
        } else {
            $wishlist = session()->get('wishlist', []);
            if (!in_array($proid, $wishlist)) {
                $wishlist[] = $proid;
                session()->put('wishlist', $wishlist);
                return Response(['status' => 'success', 'message' => 'Product added to your wishlist']);
            }
            return Response(['status' => 'warning', 'message' => 'Product already in your wishlist']);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->idx == 0){
            $proid = proidget($request->idx);
            if(Auth::check()) {
                Wishlist::where('user_id', Auth::id())->delete();
            } else {
                session()->forget('wishlist');
            }
            return Response(['status' => 'success', 'message' => 'All products removed from wishlist']);
        } else {
            $proid = proidget($request->idx);
            if(Auth::check()) {
                $Wishlist = Wishlist::where('product_id', $proid)->where('user_id', Auth::id())->delete();
                //$Wishlist = Wishlist::findOrFail($proid);
                //$Wishlist->delete();
            } else {
                $wishlist = session()->get('wishlist', []);
                if (($key = array_search($proid, $wishlist)) !== false) {
                    unset($wishlist[$key]);
                }
                session()->put('wishlist', $wishlist);
            }
            return Response(['status' => 'success', 'message' => 'Product removed from wishlist']);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
