<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'hd_quote';

    protected $fillable = [
        'dateUpdateQuote',
        'dateCreationQuote',
        'dateExpiration',
        'conditionPaiment',
        'quoteNumber',
        'varNumber',
        'quoteTitle',
        'quantitySelect',
        'quantity',
        'varQuantities',
        'delai',
        'autreContact',
        'emailContact',
        'telContact',
        'mobileContact',
        'optionPasEnvoiAuto',
        'txtNbModel',
        'txtAutreNbModel',
        'txtOptionPao',
        'txtPrepressPao',
        'txtDescription',
        'txtAssemblageSelect',
        'txtAssemblage',
        'txtRemGeneral',
        'txtEmballage',
        'txtTransportSelect',
        'txtExpedition',
        'txtFinitionGenralSelect',
        'txtFaconnageFinGenral',
        'prixPropose',
        'prixPao',
        'prixFournisseur',
        'prixValide',
        'quoteStatus',
        'adressFacturationOne',
        'adressFacturationTwo',
        'zipCodeFacturation',
        'cityFacturation',
        'txtPrecisionFacturation',
        'commercialName',
        'commercialCode',
        'quoteSupplier',
        'isInjectedInCrm',
        'txtCodeRefus',
        'txtCmtActionsRefusOrAcceptation',
        'typeEmballage',
        'qteGlobalEmballage',
        'idfkcontact',
        'idfkclient',
        'idfkfamilyproduct',
        'idFkSupplier',
        'idRfqRetenu',
        'idfkUser',
        'signe',
        'pathsigne',
        'isUrgent',
        'idProductQuote',
        'currency_id',
        'project_manager_id',
    ];
}
