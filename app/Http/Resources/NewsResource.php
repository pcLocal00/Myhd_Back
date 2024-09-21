<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "header"=> $this->header,
            "subheader"=> $this->subheader,
            "description"=> $this->description,
            "image" => $this->image
        ];
    }
}
