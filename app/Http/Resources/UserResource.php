<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authUser = auth(guard:'api')->user(); 
    
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'username' => $this->username,
            'image' => $this->image,
            'posts' => PostResource::collection($this->posts()->orderBy('created_at', 'desc')->get()),
            'postCount' => $this->posts()->count(),
            'followersCount' => $this->followers()->count(),
            'followingCount' => $this->followings()->count(),
            'isFollowing' => $this->followers()->where('follower_id', $authUser->id)->exists(),

        ];
    }
    
}
