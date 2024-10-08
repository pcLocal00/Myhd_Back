<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job';
    protected $primaryKey = 'idJob';

    protected $fillable = [
        'idJob','jobnumber','numWTP','numberCdePrintFlux','numcsp','versioncsp','date','title','product','quantity','batdate','batSendmailDate',
        'batFirstRevivalMailDate','batLastRevivalMailDate','successSendMailBat','cptRevival','isAutorizeToAutoRevival','departuredate','departureState',
        'printDate','printState','finishingDate','finishingState','bipDate','fabname','token','tokenDate','jobstatusid','jobstatesid','contactsid',
        'customerid','nbmodels','xml_techdatas','estimatePrice','price','priceFournisseur','pricePao','priceCommission','PriceOldCommission','totalTaxCsp',
        'estimateNumber','estimateVariantNumber','oldJobNumber','productRef','orderNumber','expeditionPrevueDate','deliveryDate','deliveryName',
        'thirdPartyRef','printingNotes','finishingNotes','deliveryNotes','finishingInformations','invoicingInformations','SalesRepresentativeCode',
        'SalesRepresentativeName','gamesys','productId','packingId','txtEmballage','prepressId','webToEasily','webserviceWebToEasily','is_amalgame',
        'path_file_amalgame','auto_impression','num_panier','numColis','urlTracking','planified','delai_imperative','is_printflux','notifMailExpedition',
        'jobnumber_printflux','order_prinflux','id_amalgame','idclientpintflux','identifiantProduit','formatpf','profilpf','paperpf','manuelle','idexport',
        'idlogpf','exportid','idFkSupplier','is_myhdcommande','revised','selected','ifkquote','date_etat_cmd','id_jobstatusproduction','num_commande_externe',
        'delaiSouhaiteAb','idFacture','delaiLivraisonFr','quantite_expedie_job','linkVisualSm','linkVisualMd','linkVisualLg','linkVisualPdf','numBdcExterne',
        'codeImputation','comment','prixLivraison','LINKPDFBAT','datemodifsynchrowtp','Commercialname','Commercialcode','Idfkuser','idfab','isactive_cmd',
        'priseencharge','priseenchargefrs','priseenchargedate','frsname','isAssociedWithFactureFromXls','currency_id','project_manager_i',
    ];

}


