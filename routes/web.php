<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        /* Site */
        Route::get('/', [\App\Http\Controllers\SiteController::class, 'index']);
        Route::get('/all-ads', [\App\Http\Controllers\AdSearchController::class, 'allAds']);
        Route::get('/ads-by/{id}/{username}', [\App\Http\Controllers\AdSearchController::class, 'adsByUser']);
        Route::get('/ad/{id}/{title}', [\App\Http\Controllers\AdSearchController::class, 'adDetails']);
        Route::get('/ad/{id}', [\App\Http\Controllers\AdSearchController::class, 'adDetails']);

        /* Site Ajax Location and Categories */
        Route::get('/ajax/categories', [\App\Http\Controllers\SiteController::class, 'ajaxCategoryModal']);
        Route::get('/ajax/locations', [\App\Http\Controllers\SiteController::class, 'ajaxLocationModal']);


        Auth::routes();

        /* User */
        Route::get('/home', [\App\Http\Controllers\SiteController::class,'index'])->name('home');
        Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'dashboard']);
        Route::get('/favourites', [\App\Http\Controllers\HomeController::class, 'userFavourites']);
        Route::get('/account', [\App\Http\Controllers\HomeController::class, 'account']);
        Route::get('/messages', [\App\Http\Controllers\HomeController::class, 'messages']);
        Route::get('/message/{code}/{user}/{poster}', [\App\Http\Controllers\HomeController::class, 'viewMessage']);
        Route::get('/balance', [\App\Http\Controllers\HomeController::class, 'balance']);

        /* help site pages */
        Route::get('/help', [\App\Http\Controllers\HomeController::class, 'help'])->name('help');
        Route::post('/sendAdminMessage', [\App\Http\Controllers\HomeController::class, 'sendAdminMessage'])->name('sendAdminMessage');

        /* Ad management */
        Route::get('/post-ad', [\App\Http\Controllers\HomeController::class, 'postAd']);
        Route::get('/edit-ad/{id}', [\App\Http\Controllers\HomeController::class, 'editAd']);
        Route::get('/delete-ad/{id}', [\App\Http\Controllers\HomeController::class, 'deleteAd']);


        /* Edit ad */
        Route::get('/delete-post-image/{id}', [\App\Http\Controllers\HomeController::class, 'postImageEditRemove']);
        Route::any('/post-ad-image',  [\App\Http\Controllers\HomeController::class,'postAdImageHandler']);

        /* Report an ad */
        Route::post('/report', [\App\Http\Controllers\HomeController::class, 'reportAd']);

         /*User Send to friend */
         Route::post('/sendToFriend', [\App\Http\Controllers\HomeController::class,'sendToFriend']);

        /* Favourite an And */
        Route::get('/favour/{id}', [\App\Http\Controllers\HomeController::class, 'favourAd']);

        /* Promote an Ad */
        Route::get('/promote-ad/{id}', [\App\Http\Controllers\HomeController::class, 'promoteAd']);

        /* Update View Count */
        Route::get('/ajax/view/{id}/{tok}', [\App\Http\Controllers\AdSearchController::class, 'ajaxView']);
        Route::get('/threads', [\App\Http\Controllers\HomeController::class, 'threadsGet']);

        /* Account */
        Route::post('/account/update', [\App\Http\Controllers\HomeController::class, 'accountUpdate']);

        /* Recharge request */
        Route::post('/balance-request', [\App\Http\Controllers\HomeController::class, 'requestRecharge']);

        /* Post Ad */
        Route::post('/post-ad/submit', [\App\Http\Controllers\HomeController::class, 'postAdSubmit']);
        Route::post('/edit-ad/submit', [\App\Http\Controllers\HomeController::class, 'editAdSubmit']);
    }
);

/* Form Submits */

Route::post('/upload', [\App\Http\Controllers\HomeController::class, 'postImageUpload']);
Route::post('/upload-delete', [\App\Http\Controllers\HomeController::class, 'postImageDeleteCache']);


/**
 * Admin Panel Routes
 */
Route::get('/administrator', [\App\Http\Controllers\AdminLoginController::class, 'index']);
Route::post('/admin/authenticate', [\App\Http\Controllers\AdminLoginController::class, 'verifyUser']);

Route::group(['prefix' => 'admin', 'middleware' => [CheckAdmin::class]], function () {

    Route::get('/logout', [\App\Http\Controllers\AdminController::class, 'logout']);
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin');

    /* User Management */
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'usersDatatable']);
    Route::get('/users/getdata', [\App\Http\Controllers\AdminController::class, 'usersDatatableGetData'])->name('datatables/usersgetdata');

    Route::get('/users/changeStatus/{status}/{id}', [\App\Http\Controllers\AdminController::class, 'usersChangeStatus']);

    /* User recharge payments */
    Route::get('/payments', [\App\Http\Controllers\AdminController::class, 'rechargeDatatable']);
    Route::get('/payments/getdata', [\App\Http\Controllers\AdminController::class, 'rechargeDatatableGetData'])->name('datatables/rechargegetdata');
    Route::get('/payment/changeStatus/{status}/{id}', [\App\Http\Controllers\AdminController::class, 'rechargeChangeStatus']);


    /* Ad Posts Management */
    Route::get('/ads', [\App\Http\Controllers\AdminController::class, 'adsDatatable']);
    Route::get('/ads/getdata', [\App\Http\Controllers\AdminController::class, 'adsDatatableGetData'])->name('datatable/getdata');
    Route::get('/ads/changeStatus/{status}/{id}', [\App\Http\Controllers\AdminController::class, 'adsChangeStatus']);


    /* User Reports Management */
    Route::get('/ad/complains', [\App\Http\Controllers\AdminController::class, 'reportsDatatable']);
    Route::get('/ad/complains/getdata', [\App\Http\Controllers\AdminController::class, 'reportsDatatableGetData'])->name('datatable/getreportdata');
    Route::get('/ad/complain/end/{id}', [\App\Http\Controllers\AdminController::class, 'reportsEnd']);


    /*Admin Message Managment*/
    Route::get('/admin_messages', [\App\Http\Controllers\AdminController::class, 'adminMessagesDatatable']);
    Route::get('/admin_messages/getdata', [\App\Http\Controllers\AdminController::class, 'adminMessagesDatatableGetData'])->name('datatable/get_admin_messages_data');
    Route::get('/admin_messages/end/{id}', [\App\Http\Controllers\AdminController::class, 'adminMessagesEnd']);
    Route::get('/admin_messages/respond/{id}', [\App\Http\Controllers\AdminController::class, 'showAdminMessage'])->name('showAdminMessage');
    Route::post('/admin_messages/respond', [\App\Http\Controllers\AdminController::class, 'adminMessageRespond'])->name('adminMessageRespond');


    /* Category Management */
    Route::get('/categories', [\App\Http\Controllers\AdminController::class, 'categoryView']);
    Route::get('/category/create', [\App\Http\Controllers\AdminController::class, 'categoryCreate']);
    Route::get('/category/edit/{category_id}', [\App\Http\Controllers\AdminController::class, 'categoryEdit']);
    Route::post('/category/save-category', [\App\Http\Controllers\AdminController::class, 'categorySaveCategory']);

    Route::get('/subcategory/create', [\App\Http\Controllers\AdminController::class, 'subcategoryCreate']);
    Route::get('/subcategory/edit/{subcategory_id}', [\App\Http\Controllers\AdminController::class, 'subcategoryEdit']);
    Route::post('/subcategory/save-subcategory', [\App\Http\Controllers\AdminController::class, 'subcategorySave']);


    /* Location Management */
    Route::get('/locations', [\App\Http\Controllers\AdminController::class, 'locationView']);

    Route::get('/division/create', [\App\Http\Controllers\AdminController::class, 'divisionCreate']);
    Route::get('/division/edit/{division_id}', [\App\Http\Controllers\AdminController::class, 'divisionEdit']);
    Route::post('/division/save-division', [\App\Http\Controllers\AdminController::class, 'divisionSave']);

    Route::get('/city/create', [\App\Http\Controllers\AdminController::class, 'cityCreate']);
    Route::get('/city/edit/{city_id}', [\App\Http\Controllers\AdminController::class, 'cityEdit']);
    Route::post('/city/save-city', [\App\Http\Controllers\AdminController::class, 'citySave']);


    Route::get('/sample/table', [\App\Http\Controllers\AdminController::class, 'table']);
    Route::get('/sample/form', [\App\Http\Controllers\AdminController::class, 'form']);
});

/**
 * Admin Panel Routes
 */


// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
