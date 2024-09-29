<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FamilleMyHdResource;
use App\Http\Resources\FamilleResource;
use App\Models\Famille;
use App\Models\HdCatalogue;
use App\Models\RfqFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FamilleController extends Controller
{

    public function getFamille() {
        $familles = Famille::get();

        return FamilleResource::collection($familles);
    }

    public function getFamilleMyHd() {
        $familles = RfqFamily::with('parent')
            ->get()
            ->map(function ($famille) {
                return [
                    'id'=>$famille->idFamily,
                    'img' => base64_decode($famille->imagePathFamily),
                    'name' => $famille->nameFamily,
                    'code' => $famille->codeFamily,
                    'parent_name' => $famille->parent ? $famille->parent->nameFamily : null,
                ];
            });

        return response()->json(['data' => $familles], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function getOneFamilleMyHd($id) {

        $famille = RfqFamily::where('idFamily', $id)->first();
        $data = new FamilleMyHdResource($famille);
        return response()->json(['data' => $data], 200);
    }

    public function getParentMyHd() {

        $rfqfamilly = RfqFamily::get();

        $data = $rfqfamilly->map(function($item) {
            return [
                'name' => $item->nameFamily,
                'id' => $item->idFamily,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function AddFamille(Request $request) {
        Log::info($request->all());

        $validatedData = $request->validate([
            'IMG_FAMILY'                => 'nullable|image|max:1024',
            'NAME_FAMILY'               => 'required|string|max:255',
            'NUMORDER_FAMILY'           => 'nullable|integer',
            'CODE_FAMILY'               => 'nullable|string|max:45',
            'DELAI_FAMILY'              => 'nullable|integer',
            'DESC_FAMILY'               => 'nullable|string|max:255',
            'SELECT_PARENT_FAMILY_1'    => 'nullable|exists:rfq_families,idFamily',
            'ACTIVE_FAMILY'             => 'required|boolean',
            'SHOW_IN_CATALOGUE_FAMILY'  => 'nullable|boolean',
        ]);

        $data = [
            'codeFamily'            =>  $validatedData['CODE_FAMILY'] ?? null,
            'nameFamily'            =>  $validatedData['NAME_FAMILY'],
            'descFamily'            =>  $validatedData['DESC_FAMILY'] ?? null,
            'imagePathFamily'       =>  isset($validatedData['IMG_FAMILY'])
                                          ? base64_encode(file_get_contents($validatedData['IMG_FAMILY']->getRealPath()))
                                          : null,
            'numOrderFamily'        =>  $validatedData['NUMORDER_FAMILY'] ?? null,
            'enabledFamily'         =>  $validatedData['ACTIVE_FAMILY'],
            'isShowInCatalogue'     =>  $validatedData['SHOW_IN_CATALOGUE_FAMILY'] ?? null,
            'delaiLivraison'        =>  $validatedData['DELAI_FAMILY'] ?? null,
            'idParentFamily'        =>  $validatedData['SELECT_PARENT_FAMILY_1'] ?? null,
        ];

        RfqFamily::create($data);

        return response()->json(['message' => 'Family added successfully'], 201);
    }

    public function updateFamille(Request $request, $id) {

        Log::info($request->all());

        $validatedData = $request->validate([
            'IMG_FAMILY'                => 'nullable|image|max:1024',
            'NAME_FAMILY'               => 'required|string|max:255',
            'NUMORDER_FAMILY'           => 'nullable|integer',
            'CODE_FAMILY'               => 'nullable|string|max:45',
            'DELAI_FAMILY'              => 'nullable|integer',
            'DESC_FAMILY'               => 'nullable|string|max:255',
            'SELECT_PARENT_FAMILY_1'    => 'nullable|exists:rfq_families,idFamily',
            'ACTIVE_FAMILY'             => 'required|boolean',
            'SHOW_IN_CATALOGUE_FAMILY'  => 'nullable|boolean',
        ]);

        $catalogue = RfqFamily::where('idFamily', $id)->first();

        Log::info($catalogue);

        if (!$catalogue) {
            return response()->json(['error' => 'Catalogue not found'], 404);
        }

        $data = [
            'codeFamily'            =>  $validatedData['CODE_FAMILY'] ?? null,
            'nameFamily'            =>  $validatedData['NAME_FAMILY'],
            'descFamily'            =>  $validatedData['DESC_FAMILY'] ?? null,
            'imagePathFamily'       =>  isset($validatedData['IMG_FAMILY'])
                                          ? base64_encode(file_get_contents($validatedData['IMG_FAMILY']->getRealPath()))
                                          : $catalogue->imagePathFamily, // Keep the old image if not provided
            'numOrderFamily'        =>  $validatedData['NUMORDER_FAMILY'] ?? null,
            'enabledFamily'         =>  $validatedData['ACTIVE_FAMILY'],
            'isShowInCatalogue'     =>  $validatedData['SHOW_IN_CATALOGUE_FAMILY'] ?? null,
            'delaiLivraison'        =>  $validatedData['DELAI_FAMILY'] ?? null,
            'idParentFamily'        =>  $validatedData['SELECT_PARENT_FAMILY_1'] ?? null,
        ];

        $catalogue->update($data);

        return response()->json([
            'message' => 'Catalogue updated successfully',
            'catalogue' => $catalogue
        ]);
    }
}
