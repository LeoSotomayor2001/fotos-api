<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        Carbon::setLocale('es');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file' => $this->file,
            'file_type' => $this->file_type,
            'user_id' => $this->user_id,
            'username' => $this->user->username,
            'userImage' => $this->user->image,
            'comments' => CommentResource::collection($this->comments()->orderBy('created_at', 'desc')->get()),
            'commentsCount' => $this->comments()->count(),
            'created' => Carbon::parse($this->created_at)->diffForHumans(),
            'reactions' => [
                'like' => $this->reactions()->where('type', 'like')->count(),
                'love' => $this->reactions()->where('type', 'love')->count(),
                'haha' => $this->reactions()->where('type', 'haha')->count(),
                'sad' => $this->reactions()->where('type', 'sad')->count(),
                'angry' => $this->reactions()->where('type', 'angry')->count(),
            ],
            'reactionsCount' => $this->reactions()->count(),
            'userHasReacted' => $user ? $this->reactions()->where('user_id', $user->id)->exists() : false, // ✅ Verifica si el usuario reaccionó
            'userReactionType' => $user ? $this->reactions()->where('user_id', $user->id)->value('type') : null, // ✅ Muestra el tipo de reacción
        ];
    }
}
