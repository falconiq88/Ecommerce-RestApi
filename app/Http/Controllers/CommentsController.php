<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\support\Facades\Auth;

class CommentsController extends Controller
{
  public function  index(Request $request){

$comments=Comment::where('product_id',$request['product_id'])->get();
return response()->json($comments);


    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'body' => 'required|string',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {
            Comment::create([
                'product_id'=>$request['product_id'],
                'body'=>$request['body'],
                'user_id'=>Auth::user()->id
            ]);
            return response()->json('comment added successfully', 200);
        }


    }

    public function delete(Request $request){
$comment= Comment::with('author')->where('id',$request['comment_id'])->first();

        if(Auth::user()->id ==$comment->user_id ){
Comment::destroy($request['comment_id']);
return response()->json('comment has been deleted');

        }
        else{
            return response()->json('comment not found');
        }
    }
}
