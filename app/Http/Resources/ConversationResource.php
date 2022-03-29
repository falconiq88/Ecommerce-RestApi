<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\support\Facades\Auth;
use App\Http\Resources\MessageResource;
use App\Models\Message;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   $message=Message::where('conversation_id',$this->id)->latest()->first();
        $data['id']= $this->id;
        $data['user'] = (Auth::user()->id == $this->user_id) ? new FollowerResource(User::find($this->second_user_id)) :  new FollowerResource(User::find($this->user_id));
        $data['last_message']= new MessageResource($message);
        $data['created_at'] = $this->created_at;
        return $data;
    }
}
