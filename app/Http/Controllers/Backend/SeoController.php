<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\seoDataTable;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Models\Seo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Request as FRequest;

class SeoController extends Controller
{
    public function indexx(seoDataTable $tbl){
        return $tbl->render('backend.seo.index');
    }

    public function index(){
        return view('backend.seo.index');
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data = Seo::select('id', 'url', 'type', 'title', 'desc');  // Replace with your actual column names
            //
            return DataTables::of($data)
                ->addColumn('actions', function ($Seo) {
                    $rt = '<a href="" class="btn btn-sm btn-primary">Edit</a>
                            <form action="" method="POST" style="display:inline;">
                                '. csrf_field() .'
                                '. method_field('DELETE') .'
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>';

                    $editx = "<a href='".route('admin.seo.edit', $Seo->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                    $deletex = "<a href='".route('admin.seo.destroy', $Seo->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                    return $editx.$deletex;       
                })
                ->addColumn('Type', function($Seo){
                    if($Seo->type == 1) {
                        return "Product";
                    } elseif($Seo->type == 2) {
                        return "Page";
                    } else {
                        return "custom";
                    }
                })
                ->addColumn('URL', function($Seo){ $domain = url()->to('/');
                    if($Seo->type == 1) {
                        //return "Product@".$Seo->pid;
                        //$pro = Product::findorfail($Seo->pid);
                        //return "<a target='_blank' href='http://".request()->getHost().'/'.$pro->slug."' class='btnx btn-primaryx'>".$pro->title."</a>";
                        //return $domain.'/'.$Seo->url;
                        return "<a target='_blank' href='".$domain.$Seo->url."' class='btnx btn-primaryx'>View</a>";
                    } elseif($Seo->type == 2) {
                        //return "Page@".$Seo->pid;
                        //$page = page::findorfail($Seo->pid);
                        //return "<a target='_blank' href='http://".request()->getHost().'/'.$page->slug."' class='btnx btn-primaryx'>".$page->title."</a>";
                        //return $domain.'/'.$Seo->url;
                        return "<a target='_blank' href='".$domain.$Seo->url."' class='btnx btn-primaryx'>View</a>";
                    } else {
                        //return $Seo->pid;
                        //return $Seo->url;
                        return "<a target='_blank' href='".$Seo->url."' class='btnx btn-primaryx'>View</a>";
                    }
                })
                ->rawColumns(['actions', 'URL'])
                ->make(true);
        }
    }

    public function create(){
        $product = Product::where('sts', 1)->get();
        $page = Page::where('status', 1)->get();
        $domain = url()->to('/');
        return view('backend.seo.create', compact('product', 'page', 'domain'));
    }

    public function store(Request $request){
        $seo = new Seo();
        //
        $validate = ["seo_title" => ['required'], "seo_desc" => ['required'], "seo_keyw" => ['required'], "seo_cano" => ['URL']];
        if($request->seo_type == 0){ //custom link
            $validate["seo_name0"] = ['required', 'unique:seos,pid'];
            //
            $seo->url = $request->seo_name0;
            $seo->type = 0;
        } elseif($request->seo_type == 1){ //product
            $validate["seo_name1"] = ['required', 'unique:seos,url'];
            //
            $seo->url = $request->seo_name1;
            $seo->type = 1;
        } else { //2 - page
            $validate["seo_name2"] = ['required', 'unique:seos,url'];
            //
            $seo->url = $request->seo_name2;
            $seo->type = 2;
        }
        //
        $request->validate($validate);
        //
        $seo->title = $request->seo_title;
        $seo->desc = $request->seo_desc;
        $seo->keyword = $request->seo_keyw;
        $seo->canonical = $request->seo_cano;
        $seo->save();

        //echo "<pre>"; print_r($seo);
        flash()->success('created successfully');
        return redirect()->route('admin.seo.index');
    }

    public function edit(string $id)
    {
        $product = Product::where('sts', 1)->get();
        $page = Page::where('status', 1)->get();
        $seo = Seo::findorfail($id);
        $domain = url()->to('/');
        return view('backend.seo.edit', compact('product', 'page', 'seo', 'domain'));
    }

    public function update(Request $request, string $id){
        //dd($request->all());
        //
        $validate = ["seo_title" => ['required'], "seo_desc" => ['required'], "seo_keyw" => ['required'], "seo_cano" => ['URL']];
        if($request->seo_type == 0){ //custom link
            //$validate["seo_name0"] = ['required', 'unique:seos,pid'];
            //
            //$seo->pid = $request->seo_name0;
            //$seo->type = 0;
        } elseif($request->seo_type == 1){ //product
            //$validate["seo_name1"] = ['required', 'unique:seos,pid'];
            //
            //$seo->pid = $request->seo_name1;
            //$seo->type = 1;
        } else { //2 - page
            //$validate["seo_name2"] = ['required', 'unique:seos,pid'];
            //
            //$seo->pid = $request->seo_name2;
            //$seo->type = 2;
        }
        //
        $request->validate($validate);
        //
        $seo = Seo::findorfail($id);
        $seo->title = $request->seo_title;
        $seo->desc = $request->seo_desc;
        $seo->keyword = $request->seo_keyw;
        $seo->canonical = $request->seo_cano;
        $seo->save();

        flash()->success('updated successfully');
        return redirect()->route('admin.seo.index');
    }

    public function destroy(string $id){
        $seo = Seo::findOrFail($id);
        $seo->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }

}
