<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DevisController;
use App\Http\Controllers\Api\CatalogueController;
use App\Http\Controllers\Api\FamilleController;
use App\Http\Controllers\Api\NewsController;
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

    //***** DEVIS **** */
    Route::get('/devis',[DevisController::class,'getDevis']);
    Route::get('/devis/{id}',[DevisController::class,'getOneDevis']);
    Route::get('/pdf_devis/{idDevis}/{typeAction}',[DevisController::class,'generatePdfDevis']);

    //***** COMMANDE **** */
    Route::get('/job',[JobController::class,'getJob']);
    Route::get('/job/{id}',[JobController::class,'getOneJob']);
    Route::get('/job/historique/{id}',[JobController::class,'historiqueCommande']);
    Route::put('/job/{id}/{type}', [JobController::class, 'changeStatus']);
    //***** CATALOGUE **** */
    Route::get('/catalogue',[CatalogueController::class,'getCatalogue']);
    Route::post('/add/catalogue',[CatalogueController::class,'AddCatalogue']);
    Route::post('/update/catalogue/{id}',[CatalogueController::class,'updateCatalogue']);
    Route::get('/catalogue/myhd',[CatalogueController::class,'getCatalogueMyHd']);
    Route::get('/catalogue/myhd/{id}',[CatalogueController::class,'getOneCatalogueMyHd']);
    Route::get('/parent/myhd',[CatalogueController::class,'getParentMyHd']);
    Route::get('/hdcodetg',[CatalogueController::class,'getHdCodeTg']);

    //***** FAMILLE **** */
    Route::get('/parent/famille',[FamilleController::class,'getParentMyHd']);
    Route::get('/famille',[FamilleController::class,'getFamille']);
    Route::post('/add/famille',[FamilleController::class,'AddFamille']);
    Route::post('/update/famille/{id}',[FamilleController::class,'updateFamille']);
    Route::get('/famille/hierarchy/{id}',[ProductController::class,'showProductForm']);
    Route::get('/famille/myhd',[FamilleController::class,'getFamilleMyHd']);
    Route::get('/famille/myhd/{id}',[FamilleController::class,'getOneFamilleMyHd']);

    //***** PRODUCT **** */
    Route::post('/addtg',[ProductController::class,'addTechnicalGroups']);
    Route::get('/product/realise',[ProductController::class,'getProductRealisaPrint']);
    Route::get('/product/myhd',[ProductController::class,'getProductMyhd']);
    Route::get('/product/myhd/{id}',[ProductController::class,'getOneProductMyhd']);
    Route::get('/product/{id}',[ProductController::class,'getOneProduct']);
    Route::get('/product_action/{id}/{type}',[ProductController::class,'OptionsProductAction']);
    Route::get('/sdt_action/{id}/{type}',[ProductController::class,'sdtConfiguredOptionsElementAction']);
    Route::get('/sdt_papers_element/{id}/{type}',[ProductController::class,'sdtConfiguredPapersElement']);
    Route::post('/manage_actions',[ProductController::class,'manageActionsConfigsOptionsProductAction']);
    Route::post('/manage_element',[ProductController::class,'manageActionsConfigsOptionsElement']);

    //***** NEWS **** */
    Route::get('/news',[NewsController::class,'getNews']);
    Route::post('/add/news',[NewsController::class,'addNews']);
    Route::get('/news/{id}',[NewsController::class,'getOneNews']);
    Route::post('/update/news/{id}',[NewsController::class,'updateNews']);

});
