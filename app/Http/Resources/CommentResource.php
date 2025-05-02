<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Carbon::setLocale('es');
        return [
            'id' => $this->id,
            'username'=> $this->user->username,
            'image'=> $this->user->image,
            'user_id'=> $this->user_id,
            'post_id'=> $this->post_id,
            'comment' => $this->comment,
            'created' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
