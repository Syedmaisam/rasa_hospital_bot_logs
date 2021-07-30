<?php

namespace App\Http\Controllers;

use App\Models\keywords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\keyword_logs;

class KeywordController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = keywords::orderBy('count', 'desc')->paginate(10);

        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
          //  'detail' => 'required',
        ]);

        keywords::create($request->all());

        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        // dd($product);
        $products =  keyword_logs::where('keyword_id',$product)->orderBy('id', 'desc')->paginate(10);
        // dd($products);
        return view('products.show',compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(keywords $product)
    {
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, keywords $product)
    {
        $request->validate([
            'name' => 'required',
          //  'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('keyword.index')
                        ->with('success','keyword updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy( $product)
    {
        $product_name = keywords::where('id',$product);
        // dd($product_name->get());
        $product_name->delete();

        return redirect()->route('keyword.index')
                        ->with('success','keyword deleted successfully');
    }



    public function getData(Request $request){
        $name = Str::lower($request->name);
        $data = keywords::where('name',$name )->first();
        if($data == null){
            $keyword = keywords::create(['name' => $name]);


        }
        else{
           $keyword= keywords::find($data->id);
           $keyword->count = $data->count + 1;
           $keyword->save();
        //    $data->update(['count' => $data->count + 1]);
        }
        // dd($keyword);

        $keyword_logs = keyword_logs::create(['keyword_id'=>$keyword->id]);
        return response()->json([
            'message' => 'success',
            'status' => true
        ]);
    }
}
