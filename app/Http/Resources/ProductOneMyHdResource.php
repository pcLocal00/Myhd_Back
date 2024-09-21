<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOneMyHdResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=> $this->idProduct,
            'imagePathProduct' => base64_decode($this->imagePathProduct),
            'nameProduct' => $this->nameProduct,
            'codeProduct' => $this->codeProduct,
            'typeProduct' => $this->typeProduct,
            'descProduct' => $this->descProduct,
            'numOrderProduct' => $this->numOrderProduct,
            'priceModel' => $this->priceModel,
        ];
    }
}

