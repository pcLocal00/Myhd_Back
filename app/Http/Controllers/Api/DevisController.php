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
        ->join('customer', 'customer.idCustomer', '=', 'hd_quote.idfkclient')
        ->where('hd_quote.id',$id)
        ->select(

            "hd_quote.dateCreationQuote","hd_quote.dateUpdateQuote","hd_quote.dateExpiration","hd_quote.quoteNumber","hd_quote.quoteTitle","hd_quote.quantity",
            "hd_quote.delai","hd_quote.emailContact","hd_quote.telContact","hd_quote.mobileContact", "hd_quote.optionPasEnvoiAuto", "hd_quote.txtOptionPao",
            "hd_quote.txtExpedition","hd_quote.prixPropose","hd_quote.prixPao","hd_quote.prixFournisseur","hd_quote.prixValide","hd_quote.quoteStatus","hd_quote.adressFacturationOne",
            "hd_quote.zipCodeFacturation","hd_quote.cityFacturation","hd_quote.commercialName","hd_quote.commercialCode","hd_quote.isInjectedInCrm","hd_quote.signe",
            "hd_quote.isUrgent","job.jobnumber","job.date","job.title","job.product","job.batdate","job.successSendMailBat","job.cptRevival","job.isAutorizeToAutoRevival",
            "job.fabname","job.estimatePrice","job.price","job.priceFournisseur","job.pricePao","job.priceCommission","job.estimateNumber","job.estimateVariantNumber",
            "job.expeditionPrevueDate","job.notifMailExpedition","job.is_myhdcommande","job.delaiLivraisonFr","job.quantite_expedie_job","job.isactive_cmd",
            "job.Commercialname", "job.Commercialcode",'customer.intraprintcode','customer.adresse','customer.city','customer.namecustomer','customer.emailPrincipal',

        )->first();

		return response()->json(['devis' => $devis]);
    }

    public function generatePdfDevis($idDevis, $typeAction, $signe = null)
    {
        return $this->devisService->generateDevisPdf($idDevis, $typeAction, $signe);
    }

}




