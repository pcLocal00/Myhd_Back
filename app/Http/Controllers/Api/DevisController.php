<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Services\DevisService;

class DevisController extends Controller
{
    protected $devisService;

    public function __construct(DevisService $devisService)
    {
        $this->devisService = $devisService;
    }

    public function getDevis()
    {
        $devis = Devis::join('job', 'job.ifkquote', '=', 'hd_quote.id')
        ->select(
            'hd_quote.id',
            'hd_quote.quotetitle',
            'hd_quote.quotenumber',
            'hd_quote.datecreationquote',
            'hd_quote.idfksupplier',
            'hd_quote.quantityselect',
            'hd_quote.quantity',
            'hd_quote.prixpropose',
            'hd_quote.delai',
            'hd_quote.quotestatus',
            'job.product',
        )->take(10)->get();

        return response()->json($devis);
    }

    public function getOneDevis($id)
    {
        $devis = Devis::join('job', 'job.ifkquote', '=', 'hd_quote.id')
        ->where('hd_quote.id',$id)
        ->select(
            'hd_quote.id',
            'hd_quote.quotetitle',
            'hd_quote.quotenumber',
            'hd_quote.datecreationquote',
            'hd_quote.idfksupplier',
            'hd_quote.quantityselect',
            'hd_quote.quantity',
            'hd_quote.prixpropose',
            'hd_quote.delai',
            'hd_quote.quotestatus',
            'job.product',
        )->first();

		return response()->json(['devis' => $devis]);
    }

    public function generatePdfDevis($idDevis, $typeAction, $signe = null)
    {
        return $this->devisService->generateDevisPdf($idDevis, $typeAction, $signe);
    }

}
