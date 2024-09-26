<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilleMyHdResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'=> $this->idFamily,
            'NAME_FAMILY'=> $this->nameFamily,
            'NUMORDER_FAMILY'=> $this->numOrderFamily,
            'CODE_FAMILY'=> $this->codeFamily,
            'DELAI_FAMILY'=> $this->delaiLivraison,
            'ID_PARENT'=> $this->parent ? $this->parent->idFamily : '--',
            'DESC_FAMILY'=> $this->descFamily,
            'ACTIVE_FAMILY'=> $this->enabledFamily ,
            'SHOW_IN_CATALOGUE_FAMILY'=> $this-> isShowInCatalogue,
            'IMG_FAMILY' => base64_decode($this->imagePathProduct),
        ];
    }
}
