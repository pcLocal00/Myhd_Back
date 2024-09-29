<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductMyHdResource;
use App\Http\Resources\ProductOneMyHdResource;
use App\Http\Resources\ProductPaPResource;
use App\Models\HdCatalogue;
use App\Models\HdCodeTg;
use App\Models\HdLineTg;
use App\Models\HdTechnicalGroups;
use App\Models\Product;
use App\Models\RfqProduct;
use App\Services\CatalogueTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class ProductController extends Controller
{
    public function getProductMyhd() {
        $products = RfqProduct::all();
        return ProductMyHdResource::collection($products);
    }

    public function getOneProductMyhd($id) {
        $product = RfqProduct::where('idProduct',$id)->first();
        return new ProductOneMyHdResource($product);
    }

    public function getProductRealisaPrint() {
        $products = Product::join('nv_famille','nv_famille.id','=','nv_product.famille_id')
        ->select("nv_product.id" ,"nv_product.title", "nv_famille.title as parent")->get();

        return ProductPaPResource::collection($products);
    }

    public function getOneProduct($id) {
        $product = Product::find($id)->first();

        return $product;
    }

    public function showProductForm($id) {
        $product = RfqProduct::where('idProduct',$id)->first();

        if (!$product) {return response()->json(['error' => 'Product not found'], 404); }

        $hdCatalogue = new HdCatalogue();
        return response()->json(['data' => $hdCatalogue->getHierarchyAsJson($product->idFkFamily)]);
    }

    public function OptionsProductAction($idProduct, $type) {
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
                    'type' => $type,
                    'category' => 'param',
                    'default' => $q->isDefaultValue == 1,
                    'required' => $q->isRequiredValue == 1
                ];

                $data[] = $rowData;
            }
        }

        return response()->json(['data' => $data]);
    }



    public function sdtConfiguredOptionsElementAction($idProduct, $type) {
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

                $tabjson[] = [
                    'id'=> $q->id,
                    'ordre' => $q->ordreshow > 0 ? $q->ordreshow : 0,
                    'valeur' => $q->tg ? $q->tg->valeur : '',
                    'type' => $type,
                    'category' => 'sdt',
                    'default' => $q->isDefaultValue == 1,
                    'required' => $q->isRequiredValue == 1
                ];
            }
        }

        return response()->json(['data' => $tabjson]);
    }

    public function sdtConfiguredPapersElement($idProduct) {

        $catalogueTools = new CatalogueTools();

        $idElement = RfqProduct::where('idProduct',$idProduct)
            ->join('rfq_product_element','rfq_product_element.idFkRfqProduct','=','rfq_product.idProduct')
            ->value('rfq_product_element.idProductElement');

        $results = $catalogueTools->getLinesPapersElement($idElement);
        $data = [];

        foreach ($results as $lp) {
            if ($lp->rfqPaper && $lp->rfqPaper->isActifMyHd == 1) {
                $record = [
                    'id'=> $lp->id,
                    'order' => $lp->orderconfigpaper > 0 ? $lp->orderconfigpaper : 0,
                    'paper' => [
                        'cfam_paper' => $lp->rfqPaper->cfamPaper,
                        'lfam_paper' => $lp->rfqPaper->lfamPaper,
                        'type_paper' => $lp->rfqPaper->ltypePaper,
                        'color_paper' => $lp->rfqPaper->lcoulpaper,
                        'rapid_paper' => $lp->rfqPaper->crapidePaper,
                        'grammage' => $lp->rfqPaper->grammPaper . ' g/m2'
                    ],
                    'type'=>31,
                    'default' => $lp->selected == 1,
                    'required' => $lp->selected == 1
                ];

                $data[] = $record;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function addTechnicalGroups(Request $request) {
        $validatedData = $request->validate([
            'TG_CODE' => 'required|string|max:255',
            'TG_CODE_RAPID' => 'nullable|string|max:255',
            'TG_VALUE' => 'required|string|max:255',
            'TG_TYPE_GROUP' => 'required|string|max:255',
            'TG_REQUIREDTXTFIELD' => 'string',
            'TG_LABELTXTFIELD' => 'nullable|string|max:255',
        ]);

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

    public function manageActionsConfigsOptionsProductAction(Request $request) {
        $success = false;

        if ($request->isMethod('post')) {
            $data = $request->all();

            $typeOptions = $data['TYPE_OPTION'];
            $code = $this->getCodeFromTypeOption($typeOptions);

            $type = $data['TYPE_ACTION'];
            $idProduct = $data['ID_PRODUIT'];
            $idLineTg = $data['ID_LINE_TG'];

            if ($idProduct > 0 && $idLineTg > 0) {
                switch ($type) {
                    case 'DEFAULT':
                        $this->handleDefaultAction($idProduct, $code, $idLineTg, $typeOptions);
                        $success = true;
                        break;

                    case 'DELETE':
                        // Handle delete action
                        $lineTg = HdLineTg::find($idLineTg);
                        if ($lineTg) {
                            $lineTg->delete();
                            $success = true;
                        }
                        break;

                    case 'NOT_DEFAULT':
                        // Handle not default action
                        HdLineTg::where('id', $idLineTg)->update(['ISDEFAULTVALUE' => 0]);
                        $success = true;
                        break;

                    case 'REQUIRED':
                        // Handle required action
                        HdLineTg::where('id', $idLineTg)->update(['ISREQUIREDVALUE' => 1]);
                        $success = true;
                        break;

                    case 'NOT_REQUIRED':
                        // Handle not required action
                        HdLineTg::where('id', $idLineTg)->update(['ISREQUIREDVALUE' => 0]);
                        $success = true;
                        break;
                }
            }
        }

        return response()->json(['success' => $success]);
    }

    public function manageActionsConfigsOptionsElement(Request $request) {

        $success = false;
        $dataPosted = $request->all();
        $typeOptions = $dataPosted['TYPE_OPTION'];
        $type = $dataPosted['TYPE_ACTION'];
        $idLineTg = $dataPosted['ID_LINE_TG'];
        $code = $this->getCodeFromTypeOptionEl($typeOptions);

        $idElement = RfqProduct::where('idProduct', $dataPosted['ID_PRODUIT'])
            ->join('rfq_product_element', 'rfq_product_element.idFkRfqProduct', '=', 'rfq_product.idProduct')
            ->value('rfq_product_element.idProductElement');

        if ($idElement > 0 && $idLineTg > 0) {
            $line = HdLineTg::find($idLineTg);
            if (!$line) {
                return response()->json(['success' => $success]);
            }
            log::info('Logging element info', [
                'idElement' => $idElement,
                'code' => $code,
                'idLineTg' => $idLineTg,
                'typeOptions' => $typeOptions
            ]);

            switch ($type) {
                case 'DEFAULT':
                    $this->handleDefaultElement($idElement, $code, $idLineTg, $typeOptions);
                    $success = true;
                    break;

                case 'DELETE':
                    $line->delete();
                    $success = true;
                    break;

                case 'NOT_DEFAULT':
                    $line->isDefaultValue = 0;
                    break;

                case 'REQUIRED':
                    $line->isRequiredValue = 1;
                    break;

                case 'NOT_REQUIRED':
                    $line->isRequiredValue = 0;
                    break;

                default:
                    return response()->json(['success' => $success]);
            }

            if (!$success && $line->save()) {
                $success = true;
            }
        }

        return response()->json(['success' => $success]);
    }

    private function getCodeFromTypeOptionEl($typeOptions) {
        switch ($typeOptions) {
            case 2:
                return 'HD_PRINT';
            case 3:
                return 'HD_PAGE';
            case 4:
                return 'HD_FINITION';
            case 5:
                return 'HD_FACONNAGE';
            case 6:
                return 'HD_FORMAT_OUVERT';
            default:
                return 'HD_FORMAT';
        }
    }

    private function getCodeFromTypeOption($typeOptions) {
        switch ($typeOptions) {
            case 2:
                return 'HD_G_EMBALLAGE';
            case 3:
                return 'HD_G_ASSEMBLAGE';
            case 4:
                return 'HD_G_MODEL';
            case 5:
                return 'HD_G_TRANSPORT';
            case 12:
                return 'HD_G_FINITION';
            case 13:
                return 'HD_PAO';
            default:
                return 'HD_QTE';
        }
    }

    private function handleDefaultElement($idElement, $code, $idLineTg, $typeOptions) {
        if (!in_array($typeOptions, [2, 4, 5])) {
            // Find existing default values
            $existingDefaults = HdLineTg::where('idfkelement', $idElement)
                ->where('code', $code)
                ->where('isdefaultvalue', 1)
                ->get();

            foreach ($existingDefaults as $line) {
                $line->isdefaultvalue = 0;
                $line->save();
            }
        }

        $line = HdLineTg::find($idLineTg);
        if ($line) {
            $line->isdefaultvalue = 1;
            $line->save();

            return true;
        }
    }

    private function handleDefaultAction($idProduct, $code, $idLineTg, $typeOptions) {
        if (!in_array($typeOptions, [2, 3, 12])) {
            HdLineTg::where('idfkproduct', $idProduct)
                ->where('code', $code)
                ->where('ISDEFAULTVALUE', 1)
                ->update(['ISDEFAULTVALUE' => 0]);
        }

        HdLineTg::where('id', $idLineTg)->update(['ISDEFAULTVALUE' => 1]);
    }
}

