<?php

namespace App\Http\Controllers\backend;

use App\DataTables\FaqDataTable;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FaqDataTable $tbl)
    {
        return $tbl->render('backend.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => ['required'],
            'answer' => ['required'],
            'category' => ['required'],
            'status' => ['required']
        ]); 

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->category = $request->category;
        $faq->status = $request->status;
        $faq->save();

        flash()->success('created successfully');
        return redirect()->route('admin.faq.index');
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
        $faq = Faq::findorfail($id);
        return view('backend.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'question' => ['required'],
            'answer' => ['required'],
            'category' => ['required'],
            'status' => ['required']
        ]); 

        $faq = Faq::findorfail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->category = $request->category;
        $faq->status = $request->status;
        $faq->save();

        flash()->success('created successfully');
        return redirect()->route('admin.faq.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return Response(['status' => 'success', 'message' => 'Deleted Successfully']);
    }
    
    /**
     * change status according to id
     */
    public function chg_sts(Request $request){
        $faq = Faq::findOrFail($request->id);
        $faq->status = $request->status == 'true' ? 1 : 0;
        $faq->save();
        return response(["message" => 'status updated']);
    }
}
