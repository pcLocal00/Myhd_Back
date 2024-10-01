<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'idCustomer';

    protected $fillable = [
        'idCustomer','intraprintcode','old_customer_code','creationDate','updateDate','customerSeq','fabname','namecustomer','siret','siren','adresse',
        'secteurGeo','codeApe','city','zipcode','country','phone','fax','siteWeb','emailPrincipal','secteur','effectif','formSociete','caAnnuel','representative','expert',
        'typeClient','domaineActivites','filiale','enabled','nb_old_devis','nb_old_commande','isDealer','isActiveWebToPrint','urlWebToPrint','tvaInra',
        'gender_contact_facturation','name_contact_facturation','firstname_contact_facturation','lastname_contact_facturation','tel_contact_facturation','mail_contact_facturation',
        'seq_contact_facturation','adresse_facturation','city_facturation','zipcode_facturation','country_facturation','limit_coface','limit_csafe','noteCreditSafe','dateCreditSafe',
        'tva','methodeReglement','modeReglement','delaiReglement','is_auto','is_relance_blocked','customerMarginCode','notifExpedition','idFkDealerConfiguration','nbVisites',
        'nbVisitesArealise','lastVisite','tauxMarge','noteGenerale','codeBp','numeroCpt','domiciliation','codeGuichet','cleRib','domiciliationIBAN','descGeneral','caPrevisionnel',
        'remFinancier','metiersMyhd','isGlobalBlocked','currency_id','isactive',
    ];
}
