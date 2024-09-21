<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\CatalogueController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cors'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/devis',[DevisController::class,'getDevis']);
    Route::get('/devis/{id}',[DevisController::class,'getOneDevis']);
    Route::get('/job',[JobController::class,'getJob']);
    Route::get('/job/{id}',[JobController::class,'getOneJob']);
    Route::get('/job/historique/{id}',[JobController::class,'historiqueCommande']);

    Route::get('/catalogue',[CatalogueController::class,'getCatalogue']);
    Route::get('/famille',[CatalogueController::class,'getFamille']);
    Route::get('/famille/hierarchy/{id}',[ProductController::class,'showProductForm']);

    Route::get('/product/realise',[ProductController::class,'getProductRealisaPrint']);
    Route::get('/product/myhd',[ProductController::class,'getProductMyhd']);
    Route::get('/product/myhd/{id}',[ProductController::class,'getOneProductMyhd']);
    Route::get('/product/{id}',[ProductController::class,'getOneProduct']);
    Route::get('/product_action/{id}/{type}',[ProductController::class,'OptionsProductAction']);
    Route::get('/sdt_action/{id}/{type}',[ProductController::class,'sdtConfiguredOptionsElementAction']);
    Route::get('/sdt_papers_element/{id}/{type}',[ProductController::class,'sdtConfiguredPapersElement']);

    Route::get('/news',[CatalogueController::class,'getNews']);
    Route::post('/news',[CatalogueController::class,'addNews']);

});
