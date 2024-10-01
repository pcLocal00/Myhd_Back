<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Title</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .footer {
            width: 100%;
            font-size: 8px;
            text-align: center;
        }

        .footer-invoice {
            bottom: -15px;
        }

        .footer-signature img {
            width: 140px;
        }

        .border-top {
            border-top: 1px solid black;
            margin-top: 10px;
            padding-top: 10px;
        }

        table {
            width: 100%;
        }

        .content {
            margin: 20px;
        }

        .client-info,
        .supplier-info,
        .commercial-info,
        .signature {
            margin-top: 20px;
        }

        .signature {
            text-align: right;
        }

        .footer p {
            margin: 0;
        }

        .footer .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <div class="header">
        <img src="{{ public_path('img/header-pdf-devis-1.jpg') }}" alt="Logo">
        <img src="{{ public_path('img/header-pdf-devis-3.jpg') }}" alt="Header Info">
    </div>

    <!-- Content Section -->
    <div class="content">

        <!-- Client Information -->
        <div class="client-info">
            <p>
                {{ $clientDetails->nameCustomer }}<br>
                {{ $clientDetails->nameClient }}<br>
                {{ $clientDetails->adressCustomer }}<br>
                {{ $clientDetails->emailCustomer }}<br>
                {{ $clientDetails->telCustomer }}
            </p>
        </div>

        <p> {{ $txtDate }} </p>
        <p> {{ $txtNdevis }} </p>
        <p> {{ $txtBodyDevis }} </p>
        {!! $html !!}
    </div>

    <!-- Footer Section -->
    <div class="footer">
        {{-- @if($isFooterInvoice == 0 && $last_page_flag) --}}
        @if($isFooterInvoice == 0 )
            @php
                $position = $supplierDetails ? '-70' : '-48';
            @endphp
            <div class="footer" style="position: absolute; bottom: {{ $position }}px;">
                <p>Suivi par {{ $companyName }} - {{ $commercialName }} - {{ $companyTel }}</p>

                @if($signaturedevis)
                    <br>
                    <img src="{{ url($signaturedevis) }}" alt="Signature">
                @endif
                <p>Bon pour accord pour commander et facturation</p>
                <p>Date - Cachet - Signature</p>

                <p>Prise en compte de votre validation, en cours d’affectation dans notre réseau.</p>
                <p>Dans l'attente de vos ordres, nous vous prions de croire en l'assurance de notre considération.</p>

                <p>
                    <strong>
                        Conformément à la loi 2008-776 de modernisation de l'économie, le délai maximal de règlement applicable est précisé dans la description ci-dessus et à défaut de {{ $delaiPayment }} sans escompte.
                    </strong>
                    <br>Nous subissons des hausses de matière première toutes les semaines, nous ne pouvons donc que garantir nos prix dans un délai de 10 jours. (Avril 2022)
                </p>

                @if ($supplierDetails)
                <table>
                    <tr>
                        <td width="60px">
                            @if ($supplierDetails->pathLogoFrs)
                                <img src="{{ public_path(base64_decode($supplierDetails->pathLogoFrs)) }}" width="80px">
                            @endif
                        </td>
                        <td align="right">
                            <p>
                                {{ $supplierDetails->SupplierName }}<br>
                                {{ $supplierDetails->SupplierAddressOne }} {{ $supplierDetails->SupplierAddressTwo }} {{ $supplierDetails->SupplierAddressThree ?? '' }}<br>
                                {{ $supplierDetails->SupplierZipcode }} {{ $supplierDetails->SupplierCity }}<br>
                                RCS {{ $supplierDetails->SupplierCity }} {{ $supplierDetails->SupplierSiren }}
                            </p>
                        </td>
                    </tr>
                </table>
            @endif


                <p>{{ $txtSignature }}</p>
            </div>
        @elseif($isFooterInvoice == 1)
            <div class="footer footer-invoice">
                @if($isDevConnect == 1)
                    <p>Conditions générales de ventes :</p>
                    <p>DEV CONNECT DIGITAL au capital social de 210 000 DH</p>
                    <p>ICE 001517071000049 - RC 44279 - IF 06524957 - CNSS 8561939</p>
                @else
                    <p>Conditions générales de ventes :</p>
                    <p>SAS HAVET DIGITAL au capital de 1000 €, R.C.S Lille Métropole 844 634 667 SIRET 84463466700019 TVA Intra FR76844634667</p>
                @endif
            </div>
        @endif
    </div>

</body>

</html>
