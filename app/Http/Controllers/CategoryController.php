<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function store(Request $request){



      Category::create(['slug'=>$request['slug'],'name'=>$request['name']]);
      return response()->json('category has been created successfully');



    }


    public function index(Request $request){


        $data=Category::all();
        return response()->json($data);
    }
}
