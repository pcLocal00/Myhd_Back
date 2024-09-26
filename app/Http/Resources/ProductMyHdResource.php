<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductMyHdResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'=> $this->idProduct,
            'imagePathProduct' => base64_decode($this->imagePathProduct),
            'nameProduct' => $this->nameProduct,
            'codeProduct' => $this->codeProduct,
            'typeProduct' => $this->typeProduct,
            'idFkFamily' => $this->family ? $this->family->nameFamily :'--' ,
        ];
    }
}
