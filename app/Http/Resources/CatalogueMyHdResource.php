<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogueMyHdResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'image_c'               => $this->img,
            'code_c'                => $this->code,
            'name_c'                => $this->name,
            'idParent'              => $this->idParent,
            'typeShow_c'            => $this->typeShow,
            'orderShow_c'           => $this->orderShow,
            'description_c'         => $this->description,
            'isActive'              => $this->isActive,
            'isShowInCatalogue'     => $this->isShowInCatalogue,
            'isVisibleClient'       => $this->isVisibleClient,
            'isVisibleInterne'      => $this->isVisibleInterne,
        ];
    }
}
