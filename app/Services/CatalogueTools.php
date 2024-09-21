<?php

namespace App\Services;

use App\Models\HdCatProduct;
use App\Models\HdLineTg;
use App\Models\RfqFamily;
use App\Models\RfqProductElementPaper;

class CatalogueTools
{
    public function getLinesTechnicalGroups($idProduct, $code, $idElement)
    {
        $query = HdLineTg::query();

        if ($idElement > 0 && !empty($code)) {
            $query->where('idfkelement', $idElement)
                  ->where('code', $code);
        } elseif ($idProduct > 0 && !empty($code)) {
            $query->where('idfkproduct', $idProduct)
                  ->where('code', $code);
        }

        $results = $query->orderBy('ordreshow', 'ASC')->get();

        return $results;
    }

    public function getFamilies()
    {
        $families = RfqFamily::where('enabledFamily', 1)
            ->orderBy('nameFamily', 'ASC')
            ->with(['product' => function ($query) {
                $query->where('enabledProduct', 1);
            }, 'product'])
            ->get();

        $result = $families->map(function ($family) {
            $products = $family->product->map(function ($product) {
                return [
                    'product_id' => $product->idProduct,
                    'product_name' => $product->nameProduct,
                ];
            });

            return [
                'parent_id' => $family->idParentFamil ?: 0,
                'family_id' => $family->idFamily,
                'name_family' => $family->nameFamily,
                'img_family' => $family->imagePathFamily,
                'products' => $products,
                'nb_family_child' => $family->product->count(),
            ];
        });

        return $result->toArray();
    }

    public function getFamiliesHierarchy($parent, $niveau, $array, $for, $idSelected = 0)
    {
        $hierarchy = [];

        foreach ($array as $noeud) {
            if ($parent == $noeud['parent_id']) {
                $familyData = [
                    'family_id' => $noeud['family_id'],
                    'parent_id' => $noeud['parent_id'],
                    'name_family' => $noeud['name_family'],
                    'img_family' => $noeud['img_family'],
                    'products' => [],
                    'children' => []
                ];

                if ($for == "CATALOGUE" && count($noeud['products']) > 0) {
                    foreach ($noeud['products'] as $product) {
                        $familyData['products'][] = [
                            'product_id' => $product['product_id'],
                            'product_name' => $product['product_name']
                        ];
                    }
                } elseif ($for == "PRODUIT" && $noeud['nb_family_child'] == 0) {
                    $familyData['is_selected'] = ($idSelected == $noeud['family_id']);
                }

                $familyData['children'] = $this->getFamiliesHierarchy($noeud['family_id'], $niveau + 1, $array, $for, $idSelected);

                $hierarchy[] = $familyData;
            }
        }

        return $hierarchy;
    }

    public function getListIdsCatsProduct($idfkproduct)
    {
        $listArray = [];

        if ($idfkproduct > 0) {
            $results = HdCatProduct::where('idfkproduct', $idfkproduct)->get();

            if ($results->isNotEmpty()) {
                foreach ($results as $cp) {
                    $listArray[] = $cp->idfkcat;
                }
            }
        }

        return $listArray;
    }

    public function getLinesPapersElement($idElement)
    {
        if ($idElement > 0) {
            return RfqProductElementPaper::where('idfkproductelement', $idElement)
                ->orderBy('orderconfigpaper', 'ASC')
                ->get();
        }

        return collect();
    }


}
