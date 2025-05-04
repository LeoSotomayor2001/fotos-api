<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuggestedUserResource extends JsonResource
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
            'name' => $this->name . ' ' . $this->lastname,
            'username' => $this->username,
            'image' => $this->image,
            'isFollowing' => $this->followers()->where('follower_id', $authUser->id)->exists(),
        ];
    }
}
