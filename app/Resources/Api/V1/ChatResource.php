<?php

namespace App\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id1' => $this->user_id1,
            'user_id1_detail' => $this->user1 ? new UserResource($this->user1) : [],
            'user_id2' => $this->user_id2,
            'user_id2_detail' => $this->user2 ? new UserResource($this->user2) : [],
        ];
    }
}
