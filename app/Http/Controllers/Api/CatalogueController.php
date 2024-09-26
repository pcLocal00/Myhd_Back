<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogueMyHdResource;
use App\Http\Resources\CatalogueResource;
use App\Models\Catalogue;
use App\Models\HdCatalogue;
use App\Models\HdCodeTg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogueController extends Controller
{
    public function getCatalogue() {
        $catalogues = Catalogue::get();

        return CatalogueResource::collection($catalogues);
    }

    public function getCatalogueMyHd() {
        $catalogues = HdCatalogue::with('parent')
            ->get()
            ->map(function ($catalogue) {
                return [
                    'id'=>$catalogue->id,
                    'name' => $catalogue->name,
                    'img' => base64_decode($catalogue->img),
                    'parent_name' => $catalogue->parent ? $catalogue->parent->name : null,
                ];
            });

        return response()->json(['data' => $catalogues], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getOneCatalogueMyHd($id) {
        $catalogue = HdCatalogue::where('id', $id)->first();

        $data = new CatalogueMyHdResource($catalogue);

        return response()->json(['data' => $data], 200);
    }

    public function getParentMyHd() {

        $hdcodetg = HdCatalogue::get();

        $data = $hdcodetg->map(function($item) {
            return [
                'name' => $item->name,
                'id' => $item->id,
            ];
        });

        return response()->json(['data' => $data]);
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

    public function AddCatalogue(Request $request) {

        Log::info($request->all());

        $validatedData = $request->validate([
            'image_c'               => 'nullable|image|max:1024',
            'code_c'                => 'required|string|max:255',
            'name_c'                => 'nullable|string|max:255',
            'idParent'              => 'nullable|string',
            'typeShow_c'            => 'nullable|string|max:255',
            'orderShow_c'           => 'nullable|integer',
            'description_c'         => 'required|string|max:255',
            'isActive'              => 'required|boolean',
            'isShowInCatalogue'     => 'nullable|boolean',
            'isVisibleClient'       => 'required|boolean',
            'isVisibleInterne'      => 'required|boolean',
        ]);

        $data = [
            'img'                   => $validatedData['image_c'] ? base64_encode(file_get_contents($validatedData['image_c']->getRealPath())) : null,
            'code'                  => $validatedData['code_c'] ? $validatedData['code_c'] : null,
            'name'                  => $validatedData['name_c'] ? $validatedData['name_c'] : null,
            'isActive'              => $validatedData['isActive'] ? 1 : 0,
            'idParent'              => $validatedData['idParent'] ? $validatedData['idParent'] : null,
            'typeShow'              => $validatedData['typeShow_c'] ? $validatedData['typeShow_c'] : null,
            'orderShow'             => $validatedData['orderShow_c'] ? $validatedData['orderShow_c'] : null,
            'description'           => $validatedData['description_c'] ? $validatedData['description_c'] : null,
            'isVisibleClient'       => $validatedData['isVisibleClient'] ? 1 : 0,
            'isVisibleInterne'      => $validatedData['isVisibleInterne'] ? 1 : 0,
            'isShowInCatalogue'     => $validatedData['isShowInCatalogue'] ? 1 : 0,
        ];

        HdCatalogue::create($data);

        return response()->json([
            'message' => 'Catalogue added successfully',
        ]);
    }

    public function updateCatalogue(Request $request, $id) {
        $validatedData = $request->validate([
            'image_c'               => 'nullable|image|max:1024',
            'code_c'                => 'required|string|max:255',
            'name_c'                => 'nullable|string|max:255',
            'idParent'              => 'nullable|string',
            'typeShow_c'            => 'nullable|string|max:255',
            'orderShow_c'           => 'nullable|integer',
            'description_c'         => 'required|string|max:255',
            'isActive'              => 'required|boolean',
            'isShowInCatalogue'     => 'nullable|boolean',
            'isVisibleClient'       => 'required|boolean',
            'isVisibleInterne'      => 'required|boolean',
        ]);

        $catalogue = HdCatalogue::findOrFail($id);

        $data = [
            'img'                   => isset($validatedData['image_c']) ? base64_encode(file_get_contents($validatedData['image_c']->getRealPath())) : null,
            'code'                  => $validatedData['code_c'],
            'name'                  => $validatedData['name_c'] ?? null,
            'isActive'              => $validatedData['isActive'] ? 1 : 0,
            'idParent'              => $validatedData['idParent'] ?? null,
            'typeShow'              => $validatedData['typeShow_c'] ?? null,
            'orderShow'             => $validatedData['orderShow_c'] ?? null,
            'description'           => $validatedData['description_c'],
            'isVisibleClient'       => $validatedData['isVisibleClient'] ? 1 : 0,
            'isVisibleInterne'      => $validatedData['isVisibleInterne'] ? 1 : 0,
            'isShowInCatalogue'     => $validatedData['isShowInCatalogue'] ? 1 : 0,
        ];

        $catalogue->update($data);

        return response()->json([
            'message' => 'Catalogue updated successfully',
            'catalogue' => $catalogue
        ]);
    }

}
