<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

            $data=Message::where('conversation_id',$request['conversation_id'])->oldest()->get();
            return MessageResource::collection($data);


        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required',
            'conversation_id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        } else {

       $message=new Message();
       $message->body=$request['body'];
       $message->read=false;
       $message->user_id=Auth::user()->id;
       $message->conversation_id=$request['conversation_id'];
       $message->created_at= Carbon::now('asia/baghdad');
       $message->save();


            $conversation = $message->conversation;
            $user = User::findOrFail($conversation->user_id == auth()->id() ? $conversation->second_user_id : $conversation->user_id);
            $user->pushNotification(auth()->user()->name . ' send you a message', $message->body, $message);
            return new MessageResource($message);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
