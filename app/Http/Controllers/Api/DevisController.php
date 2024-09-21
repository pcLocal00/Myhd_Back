<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devis;


class DevisController extends Controller
{
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
        )->take(10)->get();

		return response()->json(['devis' => $devis]);
    }
}
