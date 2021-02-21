<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class SinglePageController extends Controller
{

    public function index(){
        return $this->refresh();
    }

    public function store(Request $request){
        $product = Product::create($request->all());
        if($product){
            return $this->refresh();  
        }
    }

    public function edit($id){
        $product = Product::find($id);
        return response()->json($product);
    }

    public function update($id){
        $product = Product::find($id);
        $product->name = request('name');
        $product->price = request('price');
        $product->quantity = request('quantity');
        $product->save();
        return $this->refresh();
    }

    public function destroy($id){
        $product = Product::find($id);
        $product->delete();
        return $this->refresh();
    }

    private function refresh(){
        $products = Product::paginate(10);
        return response()->json($products);
    }
}
