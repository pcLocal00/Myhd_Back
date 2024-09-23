<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductMyHdResource;
use App\Http\Resources\ProductOneMyHdResource;
use App\Http\Resources\ProductPaPResource;
use App\Models\HdCatalogue;
use App\Models\HdCodeTg;
use App\Models\HdTechnicalGroups;
use App\Models\Product;
use App\Models\RfqProduct;
use App\Services\CatalogueTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProductMyhd()
    {
        $products = RfqProduct::all();
        return ProductMyHdResource::collection($products);
    }

    public function getOneProductMyhd($id)
    {
        $product = RfqProduct::where('idProduct',$id)->first();
        return new ProductOneMyHdResource($product);
    }

    public function getProductRealisaPrint()
    {
        $products = Product::join('nv_famille','nv_famille.id','=','nv_product.famille_id')
        ->select("nv_product.id" ,"nv_product.title", "nv_famille.title as parent")->get();

        return ProductPaPResource::collection($products);
    }


    public function getOneProduct($id)
    {
        $product = Product::find($id)->first();

        return $product;
    }

    public function OptionsProductAction($idProduct, $type)
    {
        $code = 'HD_QTE';
        $param = 0;

        switch ($type) {
            case 2:
                $code = 'HD_G_EMBALLAGE';
                $param = 7;
                break;
            case 3:
                $code = 'HD_G_ASSEMBLAGE';
                $param = 8;
                break;
            case 4:
                $code = 'HD_G_MODEL';
                $param = 9;
                break;
            case 5:
                $code = 'HD_G_TRANSPORT';
                $param = 10;
                break;
            case 12:
                $code = 'HD_G_FINITION';
                $param = 12;
                break;
            case 13:
                $code = 'HD_PAO';
                $param = 13;
                break;
        }

        $catalogue_tools = new CatalogueTools();
        $results = $catalogue_tools->getLinesTechnicalGroups($idProduct, $code, 0);

        $data = [];

        if ($results->count() > 0) {
            foreach ($results as $q) {
                $rowData = [
                    'id' => $q->id,
                    'ordre' => $code != 'HD_QTE' ? ($q->ordreShow > 0 ? $q->ordreShow : 0) : null,
                    'valeur' => $q->tg ? $q->tg->valeur : '',
                    'is_default' => $q->isdefaultvalue == 1,
                    'is_required' => $q->isrequiredvalue == 1
                ];

                $data[] = $rowData;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function showProductForm($id)
    {
        $data = [];
        $hdCatalogue = new HdCatalogue();
        return response()->json(['data' => $hdCatalogue->getHierarchyAsJson()]);
    }

    public function sdtConfiguredOptionsElementAction($idProduct, $type)
    {
        $typeMap = [
            1 => ['code' => 'HD_FORMAT', 'param' => 1],
            2 => ['code' => 'HD_PRINT', 'param' => 2],
            3 => ['code' => 'HD_PAGE', 'param' => 3],
            4 => ['code' => 'HD_FINITION', 'param' => 4],
            5 => ['code' => 'HD_FACONNAGE', 'param' => 5],
            6 => ['code' => 'HD_FORMAT_OUVERT', 'param' => 11],
        ];

        $code = $typeMap[$type]['code'] ?? 'HD_FORMAT';
        $param = $typeMap[$type]['param'] ?? 1;

        $idElement = RfqProduct::where('idProduct',$idProduct)
            ->join('rfq_product_element','rfq_product_element.idFkRfqProduct','=','rfq_product.idProduct')
            ->value('rfq_product_element.idProductElement');

        $catalogueTools = app()->make('App\Services\CatalogueTools');
        $results = $catalogueTools->getLinesTechnicalGroups(0, $code, $idElement);

        $tabjson = [];

        if ($results->isNotEmpty()) {
            foreach ($results as $q) {

                $valeur = $q->tg ? $q->tg->valeur : '';
                $defaultValue = $q->isdefaultvalue == 1 ? true : false;
                $requiredValue = $q->isrequiredvalue == 1 ? true : false;

                $tabjson[] = [
                    'id'=> $q->idFkTg,
                    'ordre' => $q->ordreshow > 0 ? $q->ordreshow : 0,
                    'valeur' => $valeur,
                    'default' => $defaultValue,
                    'required' => $requiredValue,
                ];
            }
        }

        return response()->json(['data' => $tabjson]);
    }

    public function sdtConfiguredPapersElement($idProduct)
    {

        $catalogueTools = new CatalogueTools();

        $idElement = RfqProduct::where('idProduct',$idProduct)
            ->join('rfq_product_element','rfq_product_element.idFkRfqProduct','=','rfq_product.idProduct')
            ->value('rfq_product_element.idProductElement');

        $results = $catalogueTools->getLinesPapersElement($idElement);

        $data = [];

        foreach ($results as $lp) {
            if ($lp->rfqPaper && $lp->rfqPaper->isActifMyHd == 1) {
                $record = [
                    'order' => $lp->orderconfigpaper > 0 ? $lp->orderconfigpaper : 0,
                    'paper' => [
                        'cfam_paper' => $lp->rfqPaper->cfamPaper,
                        'lfam_paper' => $lp->rfqPaper->lfamPaper,
                        'type_paper' => $lp->rfqPaper->ltypePaper,
                        'color_paper' => $lp->rfqPaper->lcoulpaper,
                        'rapid_paper' => $lp->rfqPaper->crapidePaper,
                        'grammage' => $lp->rfqPaper->grammPaper . ' g/m2'
                    ],
                    'is_default' => $lp->selected == 1
                ];

                $data[] = $record;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function addTechnicalGroups(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'TG_CODE' => 'required|string|max:255',
            'TG_CODE_RAPID' => 'nullable|string|max:255',
            'TG_VALUE' => 'required|string|max:255',
            'TG_TYPE_GROUP' => 'required|string|max:255',
            'TG_REQUIREDTXTFIELD' => 'string',
            'TG_LABELTXTFIELD' => 'nullable|string|max:255',
        ]);

        // Map the request data to the model attributes
        $data = [
            'code' => $validatedData['TG_CODE'],
            'codeValeur' => $validatedData['TG_CODE_RAPID'],
            'valeur' => $validatedData['TG_VALUE'],
            'typeGroup'=> $validatedData['TG_TYPE_GROUP'],
            'requireTxtField' => $validatedData['TG_REQUIREDTXTFIELD'] ? 1 : 0,
            'labelTxtField' => $validatedData['TG_LABELTXTFIELD'],
        ];

        HdTechnicalGroups::create($data);
    }

    public function getHdCodeTg() {

        $hdcodetg = HdCodeTg::get();

        $data = $hdcodetg->map(function($item) {
            return [
                'name' => $item->label,
                'code' => $item->code
            ];
        });

        return response()->json(['data' => $data]);
    }

}

