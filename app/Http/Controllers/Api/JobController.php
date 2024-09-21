<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HdEvents;
use App\Models\Job;
use App\Models\User;

class JobController extends Controller
{
    public function getJob()
    {
        $job = Job::join('jobstatus', 'job.jobstatusid', '=', 'jobstatus.id')
            ->join('jobstate', 'job.jobstatesid', '=', 'jobstate.id')
            ->select(
                'job.jobnumber',
                'job.estimateNumber',
                'job.date',
                'job.batdate',
                'job.numwtp',
                'job.customerid',
                'job.title',
                'job.idfksupplier',
                'job.price',
                'job.pricepao',
                'job.pricefournisseur',
                'job.pricecommission',
                'job.ifkquote',
                'job.commercialname',
                'job.jobstatusid',
                'job.commercialcode',
                'job.quantity',
                'job.contactsid',
                'job.jobstatesid',
                'job.idjob',
                'job.departuredate',
                'job.expeditionprevuedate',
                'job.deliverydate',
                'job.priseencharge',
                'job.webtoeasily',
                'job.linkvisualsm',
                'job.productref',
                'job.product',
                'job.versioncsp',
                'job.jobnumber',
                'job.fabname',
                'jobstatus.code',
                'jobstate.code as statecode',
                'jobstatus.namestatus as namestatus',

            )->orderBy('job.jobnumber', 'desc')->take(10)->get();

        return response()->json($job);
    }

    public function getOneJob($id)
    {
        $job = Job::join('jobstatus', 'job.jobstatusid', '=', 'jobstatus.id')
            ->join('jobstate', 'job.jobstatesid', '=', 'jobstate.id')
            ->select(
                'job.jobnumber',
                'job.estimateNumber',
                'job.date',
                'job.batdate',
                'job.numwtp',
                'job.customerid',
                'job.title',
                'job.idfksupplier',
                'job.price',
                'job.pricepao',
                'job.pricefournisseur',
                'job.pricecommission',
                'job.ifkquote',
                'job.commercialname',
                'job.jobstatusid',
                'job.commercialcode',
                'job.quantity',
                'job.contactsid',
                'job.jobstatesid',
                'job.idjob',
                'job.departuredate',
                'job.expeditionprevuedate',
                'job.deliverydate',
                'job.priseencharge',
                'job.webtoeasily',
                'job.linkvisualsm',
                'job.productref',
                'job.product',
                'job.versioncsp',
                'job.jobnumber',
                'job.fabname',
                'jobstatus.code',
                'jobstate.code as statecode',
                'jobstatus.namestatus as namestatus',
                
            )->where('job.idjob', $id)->first();

        return response()->json(['commande' => $job]);
    }

    public function historiqueCommande($id)
    {
        $job = Job::where('jobnumber', $id)->first();

        $tabjson = [];

        if ($job) {
            // Initialize the query builder
            $query = HdEvents::where('idjob', $job->idjob);
            $results = $query->get();

            if ($results->count() > 0) {
                foreach ($results as $h){
                    $tabjson[] = [
                        $h->typeevent,
                        $h->dateevent ? $h->dateevent->format('d-m-Y H:i:s') : '',
                        $h->contentevent,
                        $this->getNameUser($h->iduser)
                    ];
                }
            }
        }
        return response()->json(['aaData' => $tabjson]);
    }

    public function getNameUser($idUser)
    {
        // Initialize an empty name string
        $nameUser = '';

        // Fetch the user's first and last name using Eloquent
        $user = User::select('firstnameuser', 'lastnameuser')
            ->where('iduser', $idUser)
            ->first();

        // If the user exists, concatenate their first and last name
        if ($user) {
            $nameUser = $user->firstnameuser . ' ' . $user->lastnameuser;
        }

        return $nameUser;
    }
}