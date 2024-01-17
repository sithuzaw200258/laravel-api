<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest("id")->paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
            "user_id" => Auth::id(),
        ]);

        if ($request->file("photos")) {
            $photos = [];
            foreach ($request->file("photos") as $key => $photo) {
                $file_name = $photo->store("public");
                $photos[$key] = new Photo(["name"=>$file_name]);
            }

            $product->photos()->saveMany($photos);
        }

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(["message" => "Product is not found."], 404);
        }
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(["message" => "Product is not found."], 404);
        }
        if ($request->has("name")) {
            $product->name = $request->name;
        }
        if ($request->has("price")) {
            $product->price = $request->price;
        }
        if ($request->has("stock")) {
            $product->stock = $request->stock;
        }
    
        $product->update();

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(["message" => "Product is not found."], 404);
        }
        $product->delete();
        return response()->json(["message" => "Product is deleted."],204);
    }
}
