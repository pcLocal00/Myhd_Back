<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Contact;
use App\Models\HdCurrency;
use App\Models\HdEvents;
use App\Models\HdQuote;
use App\Models\Job;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class DevisService{

    protected $config;

    public function __construct()
    {
        $this->config = config('module_config');
    }

    public function generateDevisPdf($idDevis, $typeAction, $signe = null)
    {
        $devis = $this->fetchDevisWithRelations($idDevis);

        if ($devis) {
            $pdfContent = $this->buildPdfContent($devis);
            $pdf = Pdf::loadHTML($pdfContent);

            if ($devis->quotestatus == HdQuote::DEVIS_ACCEPTE) {
                $this->addSignatureToPdf($pdf, $idDevis, $signe);
            }

            return $this->outputPdf($pdf, $typeAction, $idDevis);
        }

        return false;
    }

    private function fetchDevisWithRelations($idDevis)
    {
        return HdQuote::with(['projectManager', 'familyProduct', 'client', 'contact', 'elements', 'supplier.files'])
            ->find($idDevis);
    }

    private function buildPdfContent($devis)
    {
        $civiliteContact = 'Bonjour';
        $contact = $devis->contact;

        if (isset($contact) && !empty($contact)) {
            $gender = $contact->gendercontact;
            $arrayMr = ['M', 'MR', 'Mr.', 'Dr.', 'Prof.'];
            $arrayMme = ['MISS', 'Mme', 'MRS', 'Ms.', 'Mrs.'];

            if (in_array($gender, $arrayMr)) {
                $civiliteContact = 'Monsieur';
            } elseif (in_array($gender, $arrayMme)) {
                $civiliteContact = 'Madame';
            }
        }

        // Get the client's and supplier's details
        $clientDetails = $this->getClientDetails($devis);
        $supplierDetails = $this->getSupplierDetails($devis);
        $txtBodyDevis = $civiliteContact . ',' . "\n" . 'Nous vous remercions pour votre demande et vous prions de trouver ci-dessous notre offre pour la réalisation suivante :';

        // Configuring other variables
        $companyDirection = config('site.gerant');
        $companyName = config('site.name');
        $companyTel = config('site.tel');
        $commercialName = ($devis->commercialname) ? $devis->commercialname : $companyDirection;
        $signaturedevis = 'path/to/signature.png';
        $txtDate = 'LESQUIN LE ' . Date::today()->format('d/m/Y');
        $txtNdevis = 'Devis n° ' . $devis->quoteNumber;

        // Prepress option array
        $delaiPayment = '45 jours fin de mois';
        $delaiPayment = $devis->client->delaireglement ? $devis->client->delaireglement : $delaiPayment;
        $txtSignature = $this->getSignatureTextDevis($devis->id);
        $html = $this->generateHtml($devis);
        $viewData = [
            'clientDetails' => $clientDetails,
            'supplierDetails' => $supplierDetails,
            'companyName' => $companyName,
            'commercialName' => $commercialName,
            'companyTel' => $companyTel,
            'isFooterInvoice' => 0,
            'signaturedevis' => $signaturedevis,
            'txtDate' => $txtDate,
            'txtNdevis' => $txtNdevis,
            'delaiPayment' => $delaiPayment,
            'quoteTitle' => $devis->quoteTitle,
            'txtBodyDevis'=> $txtBodyDevis,
            'txtSignature'=>$txtSignature,
            'html' => $html,
        ];

        return view('pdf.pdf_template', $viewData)->render();
    }

    private function getClientDetails($devis)
    {
        return (object)[
            'nameCustomer' => $devis->client->namecustomer .' ('.$devis->client->intraprintcode. ')' ?? '',
            'nameClient' => $devis->contact->firstname .' '.$devis->contact->lastname ?? '',
            'adressCustomer' => $devis->client->adresse . ' ' . $devis->client->zipcode . ' ' . $devis->client->city,
            'emailCustomer' => $devis->client->emailPrincipal,
            'telCustomer' => $devis->client->phone,

        ];
    }

    private function getSupplierDetails($devis)
    {
        $supplier = $devis->supplier;

        $pathLogoFrs = $supplier->files()->where('typefile', 'LOGO')->value('path') ?? '';

        return (object) [
            'SupplierName' => trim(($supplier->firstnameContact ?? '') . ' ' . ($supplier->lastnameContact ?? '')),
            'SupplierAddressOne' => $supplier->adressOne ?? '',
            'SupplierAddressTwo' => $supplier->adressTwo ?? '',
            'SupplierAddressThree' => $supplier->adressThree ?? '', // Ensure this property is available in the supplier
            'SupplierZipcode' => $supplier->zipCode ?? '',
            'SupplierCity' => $supplier->city ?? '',
            'SupplierEmail' => $supplier->emailContact ?? '',
            'SupplierPhone' => $supplier->tel ?? '',
            'SupplierSiren' => $supplier->siren ?? '',
            'pathLogoFrs' => $pathLogoFrs,
        ];
    }

    private function addSignatureToPdf($pdf, $idDevis, $signe)
    {
        if ($signe) {
            $signatureHtml = "<div style='text-align: right;'><img src='{$signe}' style='width: 100px;'/></div>";
            $pdf->loadHTML($pdf->output() . $signatureHtml);
        }
    }

    private function outputPdf($pdf, $typeAction, $idDevis)
    {
        $path_depot_pdf_devis = public_path('pdfs/devis');
        $filename = "devis_{$idDevis}.pdf";

        switch ($typeAction) {
            case 0:
                return $pdf->stream($filename);
            case 1:
                return $pdf->download($filename);
            case 2:
                $filePath = $path_depot_pdf_devis . '/' . $filename;
                Storage::put($filePath, $pdf->output());
                return $filePath;
        }
    }

    public function getCurrencyById($id)
    {
        $currency = null;

        if ($id > 0) {
            $currency = HdCurrency::where('id', $id)->value('symbol');
        }

        return $currency ?: 0;
    }

    public function generateHtml($devis)
    {
        // Get model number with fallback to 'AUTRE'
        $txtNbModel = !empty($devis->txtNbModel) && $devis->txtNbModel !== 'AUTRE'
                      ? $devis->txtNbModel
                      : $devis->txtAutreNbModel;

        $trNbModel = '';
        if (!empty($txtNbModel) && $txtNbModel !== 'SANS') {
            $trNbModel = '<tr><td style="border-top: 1px solid #ccc;" colspan="4">Modèles : ' . htmlspecialchars($txtNbModel) . '</td></tr>';
        }

        // Define prepress options
        $optionPaoPrepress = [
            'à composer' => 'à composer',
            'FPF' => 'Fichier PDF fourni',
            'FC' => 'Fichier PDF à composer',
            'FR' => 'Fichier réimpression sans changement',
            'RM' => 'Fichier réimpression avec modification'
        ];

        // Get prepress option with default to unknown if not found
        $txtOptionPao = $devis->txtOptionPao;
        $trPrepress = '';
        if (!empty($txtOptionPao)) {
            $prepressOption = $optionPaoPrepress[$txtOptionPao] ?? 'Option inconnue';
            $trPrepress = '<tr><td colspan="4" style="border-top: 1px solid #ccc;">Prépresse : ' . htmlspecialchars($prepressOption) . '</td></tr>';
        }

        // Start building HTML
        $html = '<h2></h2>
            <table style="page-break-inside:auto;" border="0" cellspacing="0" cellpadding="4">
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr style="background-color: #E6E7E8;">
                    <td colspan="4" style="border-top: 1px solid #ccc;font-size:14px;"><strong>' . htmlspecialchars($devis->quoteTitle) . '</strong></td>
                </tr>
                ' . $trNbModel . $trPrepress;

        $attributes = [
            'txtDescription'          => 'Description :',
            'txtPrepressPao'          => 'Prepress PAO :',
            'txtAssemblage'           => 'Assemblage :',
            'txtFaconnage'            => 'Façonnage :',
            'txtFinition'             => 'Finition :',
            'txtEmballage'            => 'Emballage :',
            'txtTransportSelect'      => 'Transport Select :',
            'txtExpedition'           => 'Expedition :',
            'txtRemGeneral'           => 'General Remarks :',
            'txtPrecisionFacturation' => 'Précision :'
        ];
        if (count($devis->elements) > 0 ) {
            foreach ($devis->elements as $k => $e) {
                // Faconnage
                $txtFaconnage = $e->txtfaconnage;
                if ($e->txtfaconnageselect != 'AUTRE' && $e->txtfaconnageselect != null) {
                    $stf = $e->txtfaconnageselect;
                    $txtFaconnage = rtrim($stf, ';'); // Use rtrim to remove trailing semicolon
                }

                $pFaconnage = '';
                if ($txtFaconnage != '') {
                    $pFaconnage = '<li>Assemblage et façonnage : ' . htmlspecialchars($txtFaconnage) . '</li>';
                }
                // Finition
                $txtFinEl = $e->txtfinition;
                if ($e->txtfinitionselect != 'AUTRE' && $e->txtfinitionselect != null) {
                    $stfin = $e->txtfinitionselect;
                    $txtFinEl = rtrim($stfin, ';');
                }
                $pFinition = '';
                if ($txtFinEl != '') {
                    $pFinition = '<li>Finition : ' . htmlspecialchars($txtFinEl) . '</li>';
                }
                $ulOprionFinFaconnage = $pFaconnage . $pFinition;

                // Format selection
                $format = $e->txtformat;
                if ($e->txtFormatSelect != 'AUTRE' && $e->txtFormatSelect != null) {
                    $format = $e->txtFormatSelect;
                }

                // Impression
                $print = $e->txtprint;
                if ($e->txtprintselect != 'AUTRE' && $e->txtprintselect != null) {
                    $stprint = $e->txtprintselect;
                    $print = rtrim($stprint, ';');
                }

                // Element description
                $elementdescription = $e->txtelementdescription;
                if ($e->txtelementdescriptionselect != 'AUTRE' && $e->txtelementdescriptionselect != null) {
                    $elementdescription = $e->txtelementdescriptionselect;
                }

                if ($elementdescription != '') {
                    $elementdescription = '<li>' . htmlspecialchars($elementdescription) . '</li>';
                }

                // Format ouvert
                $fmtOuvert = '';
                $labelFmtFerme = '';
                if ($e->txtelementformatouvertselect != 'AUTRE' && $e->txtelementformatouvertselect != null) {
                    $labelFmtFerme = 'fermé ';
                    $fmtOuvert = '<li>Format ouvert : ' . htmlspecialchars($e->txtelementformatouvertselect) . '</li>';
                } else if ($e->txtelementformatouvert != null) {
                    $labelFmtFerme = 'fermé ';
                    $fmtOuvert = '<li>Format ouvert : ' . htmlspecialchars($e->txtelementformatouvert) . '</li>';
                }

                $html .= '<tr>
                            <td style="border-top: 1px solid #ccc;width:20%;">' . htmlspecialchars($e->elementtitle) . '</td>
                            <td style="border-top: 1px solid #ccc;width:80%;">
                                <ul>
                                    <li>Format ' . $labelFmtFerme . ': ' . htmlspecialchars($format) . '</li>
                                    ' . $fmtOuvert . $elementdescription . '
                                    <li>Support : ' . htmlspecialchars($e->id) . ' </li>
                                    <li>Impression : ' . htmlspecialchars($print) . '</li>' . $ulOprionFinFaconnage . '
                                </ul>
                            </td>
                        </tr>';
            }
        }
        foreach ($attributes as $key => $label) {
            if (!empty($devis->$key)) {
                $html .= '<tr>
                            <td style="border-top: 1px solid #ccc;" colspan="4">' . $label . ' ' . htmlspecialchars($devis->$key) . '</td>
                        </tr>';
            }
        }
        $currency = $this->getCurrencyById($devis->currency_id);
        $prix = $devis->prixPropose;
        $qte = $devis->quantity;
        if ($devis->qdproductquote > 0) {
            $qte = $devis->quantity;
            if ($devis->quantityselect > 0) {
                $qte = $devis->quantityselect;
            } elseif ($devis->quantityselect == 'AUTRE') {
                $qte = $devis->quantity;
            }
        }
        $html .= '<tr>
            <td colspan="2" style="border-top: 1px solid #ccc;" align="center"><b>Prix HT pour ' . $qte . ' ex: ' . $prix . ' ' . $currency . '</b></td>
        </tr>';
        $html .= '</table>';

        return $html;
    }
    public function getSignatureTextDevis($idDevis, $isWTP = 0)
    {
        $ligne = null;

        if ($isWTP) {
            $job = Job::find($idDevis);
            if ($job) {
                $l1 = 'Devis accepté pour commande';
                $idCustomer = $job->customer_id;
                $nameCustomer = $idCustomer > 0 ? Client::find($idCustomer)->namecustomer : '';
                $l2 = 'Pour la société ' . $nameCustomer;

                $contact = Contact::find($job->contacts_id);
                $l3 = $contact ? 'En la personne de ' . $contact->firstname . ' ' . $contact->lastname : '';

                $dateAcceptation = $job->date;
                $l4 = 'Le ' . $dateAcceptation->format('d/m/Y');

                $ligne = [
                    'l1' => $l1,
                    'l2' => $l2,
                    'l3' => $l3,
                    'l4' => $l4,
                ];
            }
        } elseif ($idDevis > 0) {
            $quote = HdQuote::find($idDevis);
            if ($quote && $quote->quotestatus === 'Accepté') {
                $l1 = 'Devis accepté pour commande';
                $idCustomer = $quote->id_fk_client;
                $nameCustomer = $idCustomer > 0 ? Client::find($idCustomer)->namecustomer : '';
                $l2 = 'Pour la société ' . $nameCustomer;

                $hdEvent = HdEvents::where([['typeevent', 'ACCEPTATION_DEVIS'],['iddevis',$idDevis]])->latest()->first();
                $namePersonne = $hdEvent ? $hdEvent->User->firstname .' '. $hdEvent->User->lastname: '';

                $l3 = 'En la personne de ' . $namePersonne;

                $dateAcceptation = $hdEvent->dateevent ? $hdEvent->dateevent : "" ;
                $l4 = 'Le ' . $dateAcceptation->format('d/m/Y') . ' à ' . $dateAcceptation->format('H:i:s');

                $ligne = [
                    'l1' => $l1,
                    'l2' => $l2,
                    'l3' => $l3,
                    'l4' => $l4,
                ];
            }
        }

        return $ligne;
    }


}
