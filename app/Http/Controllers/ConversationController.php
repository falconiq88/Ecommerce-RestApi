<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;



class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = Conversation::where('user_id', auth()->user()->id)->orWhere('second_user_id', auth()->user()->id)->latest('updated_at')->get();
        $count = count($conversations);
        // $array = [];
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if (isset($conversations[$i]->messages->last()->id) && isset($conversations[$j]->messages->last()->id) && $conversations[$i]->messages->last()->id < $conversations[$j]->messages->last()->id) {
                    $temp = $conversations[$i];
                    $conversations[$i] = $conversations[$j];
                    $conversations[$j] = $temp;
                }
            }
        }



        return ConversationResource::collection($conversations);
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
            'user_id' => 'required',

        ]);


        if ($validator->fails()) {

            return response()->json($validator->errors());
        }
        else{
            $existingConversation = Conversation::where([
                ['user_id', '=', auth()->user()->id],
                ['second_user_id', '=', $request['user_id']]
            ])->orWhere([
                ['user_id', '=', $request['user_id']],
                ['second_user_id', '=', auth()->user()->id]
            ])->first();
         if($existingConversation)
         {
             return response()->json(['message'=>'conversation already exists','converastion_id'=> $existingConversation->id]);
         }else{
        $conversation = Conversation::create([
            'user_id' => auth()->user()->id,
            'second_user_id' => $request['user_id']
        ]);
                return response()->json(['message' => 'conversation has been created successfully','id_conversation'=>$conversation->id]);

    }
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        //
    }




    function makConversationAsReaded(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors());
        }

        $conversation = Conversation::findOrFail($request['conversation_id']);

        foreach ($conversation->messages as $message) {
            $message->update(['read' => true]);
        }

        return response()->json('success', 200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
