<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Http\Resources\ProductResource;


class FavoritesController extends Controller
{
    //
    public function store(Request $request){
if(!Auth::user()->favoritesHas($request['product_id']))
{
        Auth::user()->favorites()->attach($request['product_id'], ['deleted_at' => Carbon::now()]);
        return response()->json(['item added succesfully']);
}
else return response('the items already exist in favorite');

    }

     public function delete(Request $request)
    {
        Auth::user()->favorites()->detach($request['product_id']);

        return response()->json('item deleted successfully');
    }

    public function index(Request $request){
        return ProductResource::collection(Auth::user()->favorites);
        // return response()->json(Auth::user()->favorites);
    }
}
