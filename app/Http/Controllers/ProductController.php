<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Cache\RedisTaggedCache;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required'
        ]);
        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $product = Product::find($id);
        if($product) {
            return $product;
        }
        return response(null,404,['message' =>'Product Not Found']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if($product) {
            return $product->update($request->all());
        }
        return response(null,404,['message' => 'Product Not Found']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product) {
            return Product::destroy($id);
        }
        return response(null,404,['message' => 'Product Not Found']);
    }
    /**
     * Search to find a product
     */
    public function search(string $name)
    {
        return Product::where('name','like','%'.$name.'%')->get();
    }


}
