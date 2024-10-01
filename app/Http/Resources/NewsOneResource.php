<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsOneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "titre_news"=> $this->header,
            "sous_titre_news"=> $this->subheader,
            "description_news"=> $this->description,
            "IMAGE_NEWS" => base64_decode($this->image)
        ];
    }
}
