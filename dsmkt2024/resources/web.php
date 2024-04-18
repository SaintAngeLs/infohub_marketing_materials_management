<?php

use App\Http\Controllers\Admin\OpinionsController as AdminOpinionsController;
use App\Http\Controllers\Admin\OpinionsImagesController;
use App\Http\Controllers\BecomeDealerController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\OpinionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountPasswordController as AdminAccountPasswordController;
use App\Http\Controllers\Admin\AccountUserController as AccountUserController;
use App\Http\Controllers\Admin\CarModelCategoryController as AdminCarModelCategoryController;
use App\Http\Controllers\Admin\CarModelController as AdminCarModelController;
use App\Http\Controllers\Admin\CarModelDocumentController as AdminCarModelDocumentController;
use App\Http\Controllers\Admin\ConfigurationController as AdminConfigurationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ContactSectionController as AdminContactSectionController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\FileController as AdminFileController;
use App\Http\Controllers\Admin\ImageController as AdminImageController;
use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\LandingPageController as AdminLandingPageController;
use App\Http\Controllers\Admin\NewsImageController as AdminNewsImageController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\NewsFileController as AdminNewsFileController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\UploadImageController as AdminUploadImageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserDepartmentController as AdminUserDepartmentController;
use App\Http\Controllers\Admin\FormEmailController as AdminFormEmailController;
use App\Http\Controllers\Admin\SocialMediaController as AdminSocialMediaController;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\LeadsController as AdminLeadsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestDriveController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactDealerController;
use App\Http\Controllers\ModelController;


Route::get('/', [IndexController::class, 'index'])->name('page.index');
Route::get('o-dealerze', [PageController::class, 'index'])->name('page.aboutDealer');
Route::view('/aktualnosci', 'page.news')->name('page.news');

Route::get('/aktualnosci', [NewsController::class, 'index'])->name('page.news');
Route::get('/aktualnosc/{id}', [NewsController::class, 'show'])->name('page.newsshow');
Route::get('/dealerzy', [DealerController::class, 'index'])->name('page.dealers');

Route::get('/kove/media-o-nas', [OpinionsController::class, 'index'])->name('page.mediaOfUs');
Route::get('/kove/media-o-nas/{id}', [OpinionsController::class, 'show'])->name('page.opinionDetail');

Route::view('/nextfile', 'page.nextfile')->name('page.nextfile');
Route::view('/serwis', 'page.service')->name('page.service');
Route::view('/instukcja-obslugi', 'page.userManual')->name('page.userManual');
Route::view('/gwarancja', 'page.guarantee')->name('page.guarantee');
Route::view('/polityka-prywatności', 'page.privacyPolicy')->name('page.privacyPolicy');
Route::view('/polityka-cookies', 'page.privacyCookies')->name('page.privacyCookies');
Route::view('/kove/o-marce', 'page.brand')->name('page.brand');
Route::view('/kove/historia', 'page.history')->name('page.history');
Route::view('/kove/technologie', 'page.technologies')->name('page.technologies');
Route::view('/kove/sport', 'page.sport')->name('page.sport');
//Route::view('/kove/media-o-nas', 'page.mediaOfUs')->name('page.mediaOfUs');
Route::view('/umow-jazde-probna', 'page.testdrive')->name('page.testdrive');
Route::get('/kontakt', [ContactController::class, 'index'])->name('page.contact');


Route::view('/modele/model-500f', 'page.model-500f')->name('page.model-500f');
Route::view('/modele/model-500x', 'page.model-500x')->name('page.model-500x');

Route::view('/modele/model-510f', 'page.model-510f')->name('page.model-510f');
Route::view('/modele/model-510x', 'page.model-510x')->name('page.model-510x');

Route::view('/modele/model-450r', 'page.model-450r')->name('page.model-450r');
Route::view('/modele/model-125r', 'page.model-125r')->name('page.model-125r');
Route::view('/modele/model-321r', 'page.model-321r')->name('page.model-321r');
Route::view('/modele/model-321rr', 'page.model-321rr')->name('page.model-321rr');
Route::view('/modele/model-450rr', 'page.model-450rr')->name('page.model-450rr');
Route::view('/modele/model-mx250', 'page.model-mx250')->name('page.model-mx250');
Route::view('/modele/model-800x', 'page.model-800x')->name('page.model-800x');




Route::get('/modele/{modelName}', [ModelsController::class, 'show'])->name('models.show');

Route::get('/modelcontact', [ModelController::class, 'sendEmail'])->name('model');
Route::post('/modelcontact', [ModelController::class, 'sendEmail'])->name('model');


Route::post('/kontakt', [ContactController::class, 'index'])->name('page.contact');
Route::get('/kontaktdealer', [ContactDealerController::class, 'index'])->name('contact');
Route::post('/kontaktdealer', [ContactDealerController::class, 'index'])->name('contact');

Route::get('/umow-jazde-probna', [TestDriveController::class, 'index'])->name('page.testdrive');
Route::post('/umow-jazde-probna', [TestDriveController::class, 'sendEmail'])->name('page.testdrive.sendemail');
Route::post('/become-dealer', [BecomeDealerController::class, 'index'])->name('become-dealer');


Route::get('/wizyta-w-serwisie', [ServiceController::class, 'index'])->name('page.visitSerwis');
Route::post('/wizyta-w-serwisie', [ServiceController::class, 'sendEmail'])->name('page.visitSerwis.sendemail');


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [AdminIndexController::class, 'index'])->name('index');
    Route::get('/start', [AdminIndexController::class, 'start'])->name('start')->middleware('login');
    Route::get('/change', [AdminIndexController::class, 'change'])->name('change')->middleware('login');
    Route::post('/login', [AdminIndexController::class, 'login'])->name('login');
    Route::get('/logout', [AdminIndexController::class, 'logout'])->name('logout');

    Route::get('/mail', [AdminPasswordController::class, 'mail'])->name('password.mail');
    Route::post('/send', [AdminPasswordController::class, 'send'])->name('password.send');
    Route::get('/reset', [AdminPasswordController::class, 'reset'])->name('password.reset');
    Route::post('/reset', [AdminPasswordController::class, 'reset'])->name('password.reset');

    Route::get('/active', [AdminPasswordController::class, 'active'])->name('password.active');
    Route::post('/active', [AdminPasswordController::class, 'active'])->name('password.active');

    Route::get('/accountuser', [AccountUserController::class, 'index'])->name('accountuser.index')->middleware('login');
    Route::post('/accountuser', [AccountUserController::class, 'index'])->name('accountuser.index')->middleware('login');

    Route::get('/accountpassword', [AdminAccountPasswordController::class, 'index'])->name('accountpassword.index')->middleware('login');
    Route::post('/accountpassword', [AdminAccountPasswordController::class, 'index'])->name('accountpassword.index')->middleware('login');

    Route::get('/carmodels/up', [AdminCarModelController::class, 'up'])->name('carmodels.up')->middleware('login');
    Route::get('/carmodels/down', [AdminCarModelController::class, 'down'])->name('carmodels.down')->middleware('login');
    Route::resource('carmodels', AdminCarModelController::class)->middleware('login');
    Route::get('/carmodels/status/{id}/{status}', [AdminCarModelController::class, 'status'])->name('carmodels.status')->middleware('login');

    Route::get('/carmodelcategories/up', [AdminCarModelCategoryController::class, 'up'])->name('carmodelcategories.up')->middleware('login');
    Route::get('/carmodelcategories/down', [AdminCarModelCategoryController::class, 'down'])->name('carmodelcategories.down')->middleware('login');
    Route::resource('carmodelcategories', AdminCarModelCategoryController::class)->middleware('login');
    Route::get('/carmodelcategories/status/{id}/{status}', [AdminCarModelCategoryController::class, 'status'])->name('carmodelcategories.status')->middleware('login');

    Route::resource('carmodeldocuments', AdminCarModelDocumentController::class)->middleware('login');
    Route::get('/carmodeldocuments/history/{id}/{history}', [AdminCarModelDocumentController::class, 'history'])->name('carmodeldocuments.history')->middleware('login');
    Route::get('/carmodeldocuments/status/{id}/{status}', [AdminCarModelDocumentController::class, 'status'])->name('carmodeldocuments.status')->middleware('login');
    Route::get('/carmodeldocuments/current/{id}/{current}', [AdminCarModelDocumentController::class, 'current'])->name('carmodeldocuments.current')->middleware('login');

    Route::post('/contacts/orderby', [AdminContactController::class, 'orderby'])->name('contacts.orderby')->middleware('login');
    Route::resource('contacts', AdminContactController::class)->middleware('login');
    Route::get('/contacts/status/{id}/{status}', [AdminContactController::class, 'status'])->name('contacts.status')->middleware('login');

    /* Route::get('/contactsections/up', [AdminContactSectionController::class, 'up'])->name('contactsections.up')->middleware('login');
      Route::get('/contactsections/down', [AdminContactSectionController::class, 'down'])->name('contactsections.down')->middleware('login'); */
    Route::post('/contactsections/orderby', [AdminContactSectionController::class, 'orderby'])->name('contactsection.orderby')->middleware('login');
    Route::resource('contactsections', AdminContactSectionController::class)->middleware('login');
    Route::get('/contactsections/status/{id}/{status}', [AdminContactSectionController::class, 'status'])->name('contactsections.status')->middleware('login');

    Route::resource('departments', AdminDepartmentController::class)->middleware('login');
    Route::get('/departments/status/{id}/{status}', [AdminDepartmentController::class, 'status'])->name('departments.status')->middleware('login');


    Route::resource('news', AdminNewsController::class)->middleware('login');
    Route::get('/news/status/{id}/{status}', [AdminNewsController::class, 'status'])->name('news.status')->middleware('login');

    Route::post('/news/images/store', [AdminNewsImageController::class, 'store'])->name('newsimages.store')->middleware('login');
    Route::post('/news/images/update', [AdminNewsImageController::class, 'update'])->name('newsimages.update')->middleware('login');
    Route::get('/news/images/destroy', [AdminNewsImageController::class, 'destroy'])->name('newsimages.destroy')->middleware('login');

    Route::post('/news/files/store', [AdminNewsFileController::class, 'store'])->name('newsfiles.store')->middleware('login');
    Route::post('/news/files/update', [AdminNewsFileController::class, 'update'])->name('newsfiles.update')->middleware('login');
    Route::get('/news/files/destroy', [AdminNewsFileController::class, 'destroy'])->name('newsfiles.destroy')->middleware('login');


    Route::resource('opinions', AdminOpinionsController::class)->middleware('login');
    Route::get('/opinions/status/{id}/{status}', [AdminOpinionsController::class, 'status'])->name('opinion.status')->middleware('login');

    // Route::post('/opinions/images/store', [OpinionsImagesController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/images/update', [OpinionsImagesController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/images/destroy', [OpinionsImagesController::class, 'destroy'])->name('opinion.destroy')->middleware('login');
    // Route::post('/opinions/files/store', [AdminNewsFileController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/files/update', [AdminNewsFileController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/files/destroy', [AdminNewsFileController::class, 'destroy'])->name('opinion.destroy')->middleware('login');


    // Route::post('/opinions/images/store', [AdminOpinionsController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/images/update', [AdminOpinionsController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/images/destroy', [AdminOpinionsController::class, 'destroy'])->name('opinion.destroy')->middleware('login');

    // Route::post('/opinions/files/store', [AdminOpinionsController::class, 'store'])->name('opinions.store')->middleware('login');
    // Route::post('/opinions/files/update', [AdminOpinionsController::class, 'update'])->name('opinionsfiles.update')->middleware('login');
    // Route::get('/opinions/files/destroy', [AdminOpinionsController::class, 'destroy'])->name('opinionsfiles.destroy')->middleware('login');


    Route::resource('users', AdminUserController::class)->middleware('login')->middleware('login');
    Route::get('/users/status/{id}/{status}', [AdminUserController::class, 'status'])->name('users.status')->middleware('login');

    Route::resource('opinions', AdminOpinionsController::class)->middleware('login');
    Route::get('/opinions/status/{id}/{status}', [AdminOpinionsController::class, 'status'])->name('opinion.status')->middleware('login');

    // Route::post('/opinions/images/store', [OpinionsImagesController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/images/update', [OpinionsImagesController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/images/destroy', [OpinionsImagesController::class, 'destroy'])->name('opinion.destroy')->middleware('login');
    // Route::post('/opinions/files/store', [AdminNewsFileController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/files/update', [AdminNewsFileController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/files/destroy', [AdminNewsFileController::class, 'destroy'])->name('opinion.destroy')->middleware('login');


    // Route::post('/opinions/images/store', [AdminOpinionsController::class, 'store'])->name('opinion.store')->middleware('login');
    // Route::post('/opinions/images/update', [AdminOpinionsController::class, 'update'])->name('opinion.update')->middleware('login');
    // Route::get('/opinions/images/destroy', [AdminOpinionsController::class, 'destroy'])->name('opinion.destroy')->middleware('login');

    // Route::post('/opinions/files/store', [AdminOpinionsController::class, 'store'])->name('opinions.store')->middleware('login');
    // Route::post('/opinions/files/update', [AdminOpinionsController::class, 'update'])->name('opinionsfiles.update')->middleware('login');
    // Route::get('/opinions/files/destroy', [AdminOpinionsController::class, 'destroy'])->name('opinionsfiles.destroy')->middleware('login');

    Route::resource('users', AdminUserController::class)->middleware('login')->middleware('login');
    Route::get('/user/departments', [AdminUserDepartmentController::class, 'index'])->name('userdepartments.index')->middleware('login');
    Route::get('/user/departments/create', [AdminUserDepartmentController::class, 'create'])->name('userdepartments.create')->middleware('login');
    Route::post('/user/departments/store', [AdminUserDepartmentController::class, 'store'])->name('userdepartments.store')->middleware('login');
    Route::get('/user/departments/edit', [AdminUserDepartmentController::class, 'edit'])->name('userdepartments.edit')->middleware('login');
    Route::post('/user/department/update', [AdminUserDepartmentController::class, 'update'])->name('userdepartments.update')->middleware('login');
    Route::get('/user/departments/destroy', [AdminUserDepartmentController::class, 'destroy'])->name('userdepartments.destroy')->middleware('login');

    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index')->middleware('login');
    Route::post('/pages', [AdminPageController::class, 'index'])->name('pages.index')->middleware('login');

    Route::post('/images/store', [AdminImageController::class, 'store'])->name('images.store')->middleware('login');
    Route::post('/images/update', [AdminImageController::class, 'update'])->name('images.update')->middleware('login');
    Route::get('/images/destroy', [AdminImageController::class, 'destroy'])->name('images.destroy')->middleware('login');

    Route::post('/files/store', [AdminFileController::class, 'store'])->name('files.store')->middleware('login');
    Route::post('/files/update', [AdminFileController::class, 'update'])->name('files.update')->middleware('login');
    Route::get('/files/destroy', [AdminFileController::class, 'destroy'])->name('files.destroy')->middleware('login');

    Route::get('/landingpages', [AdminLandingPageController::class, 'index'])->name('landingpages.index')->middleware('login');
    Route::post('/landingpages', [AdminLandingPageController::class, 'index'])->name('landingpages.index')->middleware('login');

    Route::get('/configurations', [AdminConfigurationController::class, 'index'])->name('configurations.index')->middleware('login');
    Route::post('/configurations', [AdminConfigurationController::class, 'index'])->name('configurations.index')->middleware('login');

   Route::post('/images/upload', [AdminUploadImageController::class, 'upload'])->name('images.upload')->middleware('login');

    Route::get('/formemails', [AdminFormEmailController::class, 'index'])->name('formemails.index')->middleware('login');
    // Route::get('/formemails/{type}', [AdminFormEmailController::class, 'index'])->name('formemails.index')->middleware('login');
    Route::get('/formemails/{type}/list', [AdminFormEmailController::class, 'list'])->name('formemails.list')->middleware('login');
    Route::get('/formemails/{type}/hide', [AdminFormEmailController::class, 'hide'])->name('formemails.hide')->middleware('login');
    Route::get('/formemails/createemail/{type}', [AdminFormEmailController::class, 'create'])->name('formemails.create')->middleware('login');
    Route::get('/formhideemails/createemail/{type}', [AdminFormEmailController::class, 'createHideEmail'])->name('formhideemails.create')->middleware('login');
    Route::post('/formemails/storeemail/{type}', [AdminFormEmailController::class, 'store'])->name('formemails.store')->middleware('login');
    Route::post('/formhideemails/storeemail/{type}', [AdminFormEmailController::class, 'storeHideEmail'])->name('formhideemails.store')->middleware('login');
    Route::get('/formemails/editemail/{id}/{type}', [AdminFormEmailController::class, 'edit'])->name('formemails.edit')->middleware('login');
    Route::post('/formemails/updateemail/{id}/{type}', [AdminFormEmailController::class, 'update'])->name('formemails.update')->middleware('login');
    Route::get('/formemails/destroyemail/{id}/{type}', [AdminFormEmailController::class, 'destroy'])->name('formemails.destroy')->middleware('login');
    Route::get('/formemails/edithideemail/{id}/{type}', [AdminFormEmailController::class, 'editHideEmail'])->name('formemails.edithideemail')->middleware('login');
    Route::post('/formemails/updatehideemail/{id}/{type}', [AdminFormEmailController::class, 'updateHideEmail'])->name('formemails.updatehideemail')->middleware('login');
    Route::get('/formemails/destroyhideemail/{id}/{type}', [AdminFormEmailController::class, 'destroyHideEmail'])->name('formemails.destroyhideemail')->middleware('login');

    Route::get('/social-media', [AdminSocialMediaController::class, 'index'])->name('socialmedia.index')->middleware('login');
    Route::get('/social-media/edit/{id}', [AdminSocialMediaController::class, 'edit'])->name('socialmedia.edit')->middleware('login');
    Route::post('/social-media/update/{id}', [AdminSocialMediaController::class, 'update'])->name('socialmedia.update')->middleware('login');

    Route::get('/account', [AdminAccountController::class, 'index'])->name('account.index')->middleware('login');
    Route::post('/account', [AdminAccountController::class, 'index'])->name('account.index')->middleware('login');

    Route::get('/leads', [AdminLeadsController::class, 'index'])->name('leads.index')->middleware('login');
    Route::get('/leads/downloadContactFormLeads', [AdminLeadsController::class, 'downloadContactFormLeads'])->name('leads.downloadContactFormLeads')->middleware('login');
    Route::get('/leads/downloadDealerContactFormLeads', [AdminLeadsController::class, 'downloadDealerContactFormLeads'])->name('leads.downloadDealerContactFormLeads')->middleware('login');
    Route::get('/leads/downloadTestDriveFormLeads', [AdminLeadsController::class, 'downloadTestDriveLeads'])->name('leads.downloadTestDriveLeads')->middleware('login');
    Route::get('/leads/downloadOfferRequestLeads', [AdminLeadsController::class, 'downloadOfferRequestLeads'])->name('leads.downloadOfferRequestLeads')->middleware('login');
    Route::get('/leads/downloadFromModelLeads', [AdminLeadsController::class, 'downloadFromModelLeads'])->name('leads.downloadFromModelLeads')->middleware('login');
});
