<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:11'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        }
        else{
        $user = new User(['name' => $request->name,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
            'role' => $request->role,
            'city' => $request->city,
                'phone' => $request->phone,
        ]);
        $user->save();
    }

        return response()->json("user has been Registered",200);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {


    $credintals=request(['email','password']);
    if(!Auth::attempt($credintals)){
        return response()->json(['message'=> 'enter a valid email or password']);
    }
else{
    $user=$request->user();
    $tokenResult=$user->createToken('Personal Access Token');
    $token=$tokenResult->token;
    $token->expires_at=Carbon::now()->addWeeks(1);
    $token->save();


    return response()->json([
        'data'=>[
            'user'=>Auth::user(),
            'access_token'=>'Bearer ' . $tokenResult->accessToken,
            'expires_at'=>Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]
        ]);
    }
    }

}

 // show specific user profile
    public function show(Request $request){

        $data = User::with('products')->where('id',$request['id'])->get();

        return UserResource::collection($data);


    }


    public function logout(Request $request){
        $accessToken = auth()->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required|string|min:8',
            'new_password'=> 'required|string|min:8',
            'confirm_password'=> 'required|string|min:8|same:new_password'

        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

            if(hash::check($request['oldpassword'],Auth::user()->password)){
             Auth::user()->update([
                 'password'=>bcrypt($request['new_password']),
             ]);
                return response()->json(['message' => 'password has successfully updated ']);
            }
            else{
                return response()->json(['message'=>'old password does not match']);
            }

        }
    }




      public function storeFollowing(Request $request){
        $validator = Validator::make($request->all(), [
            'following_id' => 'required|integer',

        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

            Auth::user()->following()->attach($request['following_id']);
            return response()->json(['Done',200]);
        }
      }



    public function deleteFollowing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'following_id' => 'required|integer',

        ]);
        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

            Auth::user()->following()->detach($request['following_id']);
            return response()->json(['Done', 200]);
        }
    }


      public function getFollowers(Request $request){

        $data=User::find($request['user_id'])->followers()->get();
        //return response()->json($data);
        return FollowerResource::collection($data);

      }

    public function getFollowing(Request $request)
    {

        $data = User::find($request['user_id'])->following()->get();
        //return response()->json($data);
        return FollowerResource::collection($data);
    }



}
