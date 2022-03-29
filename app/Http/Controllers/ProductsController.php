<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;


class ProductsController extends Controller
{
    public function store(Request $request){

 $validator = Validator::make($request->all(), [
                'title' => 'required',
                'body' => 'required',
                'category_id' => 'required',
                'product_city' => 'required',
                 'brand' => 'required',
                'condition' => 'required',
                'price'=> 'required'

 ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

        if (  $product=Product::create([
                'title' => $request['title'],
                'body' => $request['body'],
                'category_id' => $request['category_id'],
                'user_id' => $request->user()->id,
                'product_city' => $request['product_city'],
                'brand' => $request['brand'],
                'condition' => $request['condition'],
                'price' => $request['price'],

            ])){
            $images = $request['image'];

            foreach ($images as  $image) {

                Image::create([
                    'url'=>$image,
                    'product_id'=>$product->id
                ]);
            }
            return response([
                'message' => 'product has been created'

            ]);
            }
        }
}

public function index(){

        return ProductResource::collection(Product::all());
}


public function show($category_id){



    $products= Product::where('category_id',$category_id)->get();
            return  ProductResource::collection($products);
        }





    public function delete(Request $request)
    { $result=Product::where('user_id',Auth::user()->id)->where('id',$request['product_id'])->delete();
        if($result){
           return response()->json('product deleted successfully');
        }
        else{
            return response()->json('not found');
        }
    }


    public function productUser(Request $request)
    {
        $data= Product::where('user_id',Auth::user()->id)->get();
        return  ProductResource::collection($data);

    }

    public function productEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'=>'required',
            'title' => 'required',
            'body' => 'required',
            'product_city' => 'required',
            'brand' => 'required',
            'condition' => 'required',
            'price' => 'required'

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

        $result = Product::where('user_id', Auth::user()->id)->where('id', $request['product_id'])->get();
        if ($result) {


            // return response()->json('product deleted successfully');
        } else {
            return response()->json('not found');
        }

    }
    }

// search by title
    public function search(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'required|string',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {


        $products= Product::where('title', 'LIKE', '%' . $request['search'] . '%')->get();
        return ProductResource::collection($products);




    }
}





}
