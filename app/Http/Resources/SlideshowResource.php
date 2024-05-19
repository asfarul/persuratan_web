<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideshowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => asset('storage/' . $this->file),
            'type' => $this->type,
            'is_active' => $this->is_active ? true : false,
            'is_syariah' => $this->is_syariah ? true : false,
        ];
    }
}
