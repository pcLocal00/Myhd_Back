<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdQuote extends Model
{
    use HasFactory;
    protected $table = 'hd_quote';
    public $timestamps = false;
    const DEVIS_ACCEPTE = 'accepted';

    protected $fillable = [
        'id','dateCreationQuote','dateUpdateQuote','dateExpiration','conditionPaiment','quoteNumber','varNumber','quoteTitle','quantitySelect',
        'quantity','varQuantities','delai','autreContact','emailContact','telContact','mobileContact','optionPasEnvoiAuto','txtNbModel','txtAutreNbModel',
        'txtOptionPao','txtPrepressPao','txtDescription','txtAssemblageSelect','txtAssemblage','txtRemGeneral','txtEmballage','txtTransportSelect','txtExpedition',
        'txtFinitionGenralSelect','txtFaconnageFinGenral','prixPropose','prixPao','prixFournisseur','prixValide','quoteStatus','adressFacturationOne','adressFacturationTwo',
        'zipCodeFacturation','cityFacturation','txtPrecisionFacturation','commercialName','commercialCode','quoteSupplier','isInjectedInCrm','txtCodeRefus',
        'txtCmtActionsRefusOrAcceptation','typeEmballage','qteGlobalEmballage','idfkcontact','idfkclient','idfkfamilyproduct','idFkSupplier','idRfqRetenu',
        'idfkUser','signe','pathsigne','isUrgent','idProductQuote','currency_id','project_manager_i',
    ];

    public function projectManager()
    {
        return $this->belongsTo(HdProjectManagers::class, 'project_manager_id');
    }

    public function familyProduct()
    {
        return $this->belongsTo(FamilyProduct::class,'idfkfamilyproduct' );
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idfkclient');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'idfkcontact');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'idFkSupplier');
    }

    public function elements()
    {
        return $this->hasMany(HdElementsQuote::class, 'idfkquote');
    }

}

