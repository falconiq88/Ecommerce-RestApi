<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data['id'] = $this->id;
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['phone'] = $this->phone;
        $data['picture'] = $this->picture;
        $data['role']=$this->role;
        $data['followers'] = $this->followers()->count();
        $data['following'] = $this->following()->count();
        $data['is_following']=$this->isFollowedBy(Auth::user());
        $data['products'] = ProductResource::collection($this->products);
        return $data;
    }
}
