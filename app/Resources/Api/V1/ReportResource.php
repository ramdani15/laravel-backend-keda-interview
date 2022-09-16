<?php

namespace App\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $detail = [];
        if ($this->type == 'customer') {
            $detail = new UserResource($this->reportable);
        }
        return [
            'id' => $this->id,
            'type' => $this->type,
            'message' => $this->message,
            'detail' => $detail
        ];
    }
}
