<?php

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

include "admin.php";


Auth::routes();

Route::get('email-verification', 'Auth\EmailVerificationController@sendEmailVerification')->name('email-verification.send');
Route::get('email-verification/error', 'Auth\EmailVerificationController@getVerificationError')->name('email-verification.error');
Route::get('email-verification/check/{token}', 'Auth\EmailVerificationController@getVerification')->name('email-verification.check');
Route::get('email-verified', 'Auth\EmailVerificationController@verified')->name('email-verification.verified');
Route::post('account/updateProfile', 'Account\StoresController@updateProfile')->name('stores.updateProfile');
Route::post('uploadFile', 'Account\StoresController@uploadFile')->name('stores.uploadFile'); 

//ACCOUNT
Route::group(['middleware' => ['auth', 'isVerified'], 'prefix' => 'account', 'as' => 'account.', 'namespace' => 'Account'], function()
{
  Route::get('/', function () {
    return redirect(route('account.overview'));
  });
  Route::get('/overview', 'ProfileController@overview')->name('overview');
  Route::get('/review', 'ProfileController@review')->name('review');
  Route::get('/review_product', 'ProfileController@reviewProduct')->name('reviewProduct');
  Route::get('/profile-edit', 'ProfileController@editProfile')->name('profile-edit');

  Route::resource('change_password', 'PasswordController');
  Route::resource('edit_profile', 'ProfileController');

  Route::get('/deliveraddress', 'ProfileController@address')->name('edit_profile.address');
  Route::get('/createaddress', 'ProfileController@createaddress')->name('edit_profile.createaddress');
  Route::post('/createaddress', 'ProfileController@storeaddress')->name('edit_profile.storeaddress');
  Route::get('/editaddress', 'ProfileController@editaddress')->name('edit_profile.editaddress');
  Route::put('/editaddress/{id}', 'ProfileController@updateaddress')->name('edit_profile.updateaddress');

  Route::resource('listings', 'ListingsController');
  Route::resource('brands', 'BrandsController');
  Route::resource('stores', 'StoresController');

  Route::resource('orders', 'OrdersController');
  Route::resource('storesorder', 'StoresOrderController');

  Route::get('/store/{id}', 'StoresController@viewstore')->name('viewstore');

});

//cart
Route::resource('shop', 'CartController', ['only' => ['index', 'store', 'update', 'destroy']]);
Route::delete('emptyCart', 'CartController@emptyCart');

//LISTINGS
Route::group(['prefix' => 'listing'], function()
{
  Route::get('/{listing}/{slug}', 'ListingController@index')->name('listing');
  Route::get('/{listing}/{slug}/spotlight', 'ListingController@spotlight')->middleware('auth.ajax')->name('listing.spotlight');
  Route::get('/{listing}/{slug}/verify', 'ListingController@verify')->middleware('auth.ajax')->name('listing.verify');
  Route::get('/{listing}/{slug}/star', 'ListingController@star')->middleware('auth.ajax')->name('listing.star');
  Route::get('/{listing}/{slug}/edit', 'ListingController@edit')->name('listing.edit');
  Route::any('/{id}/update', 'ListingController@update')->name('listing.update');
});


  //DIRECTORIES
Route::group(['prefix' => 'brands'], function()
{
  Route::get('/{directory}/{slug}', 'BrandingController@index')->name('branding');
  Route::get('/{directory}/{slug}/view', 'BrandingController@showtopbrand')->name('branding.showtopbrands');
  Route::get('/{directory}/{slug}/spotlight', 'BrandingController@spotlight')->middleware('auth.ajax')->name('branding.spotlight');
  Route::get('/{directory}/{slug}/verify', 'BrandingController@verify')->middleware('auth.ajax')->name('branding.verify');
  Route::get('/{directory}/{slug}/topbrand', 'BrandingController@topbrand')->middleware('auth.ajax')->name('branding.topbrand');
  Route::get('/{directory}/{slug}/star', 'BrandingController@star')->middleware('auth.ajax')->name('branding.star');
  Route::get('/{directory}/{slug}/follow', 'BrandingController@follow')->middleware('auth.ajax')->name('branding.follow');
  Route::get('/{directory}/{slug}/edit', 'BrandingController@edit')->name('branding.edit');
  Route::any('/{id}/update', 'BrandingController@update')->name('branding.update');
});


    //DIRECTORIES
Route::group(['prefix' => 'merchant'], function()
{
  Route::get('/{shopping}/{slug}', 'ShoppingController@index')->name('shopping');
  Route::get('/{shopping}/{slug}/spotlight', 'ShoppingController@spotlight')->middleware('auth.ajax')->name('shopping.spotlight');
  Route::get('/{shopping}/{slug}/verify', 'ShoppingController@verify')->middleware('auth.ajax')->name('shopping.verify');
  Route::get('/{shopping}/{slug}/star', 'ShoppingController@star')->middleware('auth.ajax')->name('shopping.star');
  Route::get('/{shopping}/{slug}/edit', 'ShoppingController@edit')->name('shopping.edit');
  Route::any('/{id}/update', 'ShoppingController@update')->name('shopping.update');
  Route::get('{shopping}/{slug}/page', 'ShoppingController@vendor')->name('shopping.vendor');
});

  // PAGES
Route::get('/category/{shoppingcategory}/{slug}', 'ShoppingController@category')->name('shopping.category');
Route::get('/pages/{slug}', 'PageController@index')->name('page');
Route::get('/page/verification', 'PageController@notverified')->name('page.notverified');
Route::get('/directory', 'PageController@directory')->name('page.directory');
Route::get('/services', 'PageController@services')->name('page.services');
Route::get('/top-brands', 'PageController@topbrands')->name('page.topbrands');
Route::get('/comingsoon', 'PageController@comingsoon')->name('page.comingsoon');
Route::get('/aboutus', 'PageController@aboutus')->name('page.aboutus');
Route::get('/pricing', 'PageController@pricing')->name('page.pricing');
Route::get('/store-pricing', 'PageController@storepricing')->name('page.storepricing');
Route::get('/order-confirmation', 'PaymentController@orderconfirmation')->name('page.orderconfirm');
Route::get('/payment-test', 'PaymentController@testoutput')->name('page.testoutput');
  //Route::get('/payment-confirmation', 'PageController@paymentconfirmation')->name('page.payconfirmation');
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
Route::post('/getpayid', 'PaymentController@getpayid')->name('getpayid');
Route::post('/storegetpayid', 'PaymentController@storegetpayid')->name('store.getpayid');
Route::get('/payment/callback', 'PaymentController@handleGatewayCallback')->name('paycallback');

Route::get('/terms', 'PageController@termsuse')->name('page.termsuse');
Route::get('/faq', 'PageController@faq')->name('page.faq');

Route::get('/privacy-policy', 'PageController@privacypolicy')->name('page.privacypolicy');
Route::get('/disclaimer', 'PageController@disclaimer')->name('page.disclaimer');
Route::get('/billing-policy', 'PageController@billingpolicy')->name('page.billingpolicy');
Route::get('/trust-and-safety', 'PageController@trustsafety')->name('page.trustsafety');
Route::get('/contactus', 'PageController@contactus')->name('page.contactus');
Route::post('/create/contactmessage', 'PageController@contactmessage')->name('create.contactmessage');
Route::get('/subscribe', 'PageController@subscribe')->name('page.subscribe');
Route::post('/subscribe', 'PageController@postsubscribe')->name('page.postsubscribe');

  //CHECKOUT
Route::get('/checkout/{listing}', 'CheckoutController@index')->name('checkout');
Route::any('/checkout/process/{listing}', 'CheckoutController@process')->name('checkout.process');

  //CART
Route::get('/cart', 'CartController@overview')->name('cart');
Route::get('/checkout', 'CheckoutbController@index')->name('checkoutb');
Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('hometoo');

Route::get('/brands', 'BrowseController@listings')->name('browsebrand');

//Route::get('/store', 'ScanController@listings')->name('browse');

//Route::get('/stores', 'ScanController@marketplace')->name('browse');

Route::get('/stores', 'ScanController@marketplacenew')->name('browse');

Route::get('/admin', 'AdminController@index')->name('index');

Route::get('/user/admin', 'AdminController@userindex')->name('userindex');

Route::get('/admin/businesscategory', 'AdminController@managedircategory')->name('businesscategory');

Route::post('/admin/businesscategory', 'AdminController@managedircategory')->name('postbusinesscategory');

Route::get('/admin/{id}/businesscategory', 'AdminController@editdircategory')->name('edit-businesscategory');

Route::post('/admin/{id}/businesscategory', 'AdminController@editdircategory')->name('update-businesscategory');

// Sore Category
Route::get('/admin/storecategory', 'AdminController@managestorecategory')->name('storecategory');

Route::post('/admin/storecategory', 'AdminController@managestorecategory')->name('poststorecategory');

Route::get('/admin/{id}/storecategory', 'AdminController@editstorecategory')->name('edit-storecategory');

Route::post('/admin/{id}/storecategory', 'AdminController@editstorecategory')->name('update-storecategory');

//REQUIRES AUTHENTICATION
Route::group(['middleware' => ['auth']], function () {

  Route::get('email-verification', 'Auth\EmailVerificationController@index')->name('email-verification.index');
  Route::get('resend-verification', 'Auth\EmailVerificationController@resend')->name('email-verification.resend');
  //Route::get('email-verified', 'Auth\EmailVerificationController@verified')->name('email-verification.verified');

  //INBOX
  Route::resource('inbox', 'InboxController')->middleware('talk'); //Inbox

  //CREATE LISTING
  Route::resource('create', 'CreateController');
  Route::any('/create/{directory}/session', 'CreateController@session')->name('create.session');
  Route::any('/create/{directory}/sessionbrand', 'CreateController@sessionbrand')->name('create.sessionbrand');
  Route::any('/create/{directory}/sessionbanner', 'CreateController@sessionbanner')->name('create.sessionbanner');
  Route::any('/create/{directory}/sessionvideo', 'CreateController@sessionvideo')->name('create.sessionvideo');
  Route::any('/create/{directory}/sessionlogo', 'CreateController@sessionlogo')->name('create.sessionlogo');
  Route::get('/create/{directory}/images', 'CreateController@images')->name('create.images');

  Route::post('/create/{directory}/uploads', 'CreateController@upload')->name('create.upload');
  Route::any('/create/{directory}/team', 'CreateController@teamcreate')->name('create.team');

  Route::post('/create/{directory}/uploadsgallery', 'CreateController@uploadgallery')->name('create.uploadgallery');
  Route::delete('/create/{directory}/image/{uuid?}', 'CreateController@deleteUpload')->name('create.delete-image');
  Route::delete('/create/{directory}/galimage/{uuid?}', 'CreateController@deleteGalleryUpload')->name('create.delete-galimage');

  Route::post('/create/{directory}/uploadsbanner', 'CreateController@uploadbanner')->name('create.uploadbanner');
  Route::delete('/create/{directory}/bannerimage/{uuid?}', 'CreateController@deleteBannerUpload')->name('create.delete-bannerimage');

  Route::post('/create/{directory}/uploadsvideo', 'CreateController@uploadvideo')->name('create.uploadvideo');
  Route::delete('/create/{directory}/video/{uuid?}', 'CreateController@deleteVideoUpload')->name('create.delete-video');

  Route::post('/create/{directory}/uploadslogo', 'CreateController@uploadlogo')->name('create.uploadlogo');
  Route::delete('/create/{directory}/logo/{uuid?}', 'CreateController@deleteLogoUpload')->name('create.delete-logo');


      //CREATE LISTING
  Route::resource('createstore', 'CreateStoreController');
  Route::any('/createstore/{store}/session', 'CreateStoreController@session')->name('createstore.session');
  Route::get('/createstore/{store}/images', 'CreateStoreController@images')->name('createstore.images');
  Route::post('/createstore/{store}/uploads', 'CreateStoreController@upload')->name('createstore.upload');
  Route::delete('/createstore/{store}/image/{uuid?}', 'CreateStoreController@deleteUpload')->name('createstore.delete-image');

    //CREATE LISTING
  Route::get('/post/createlist', 'PostController@createlist');
  Route::resource('post', 'PostController');
  Route::any('/post/{listing}/session', 'PostController@session')->name('post.session');
  Route::get('/post/{listing}/images', 'PostController@images')->name('post.images');
  Route::get('/post/{listing}/additional', 'PostController@additional')->name('post.additional');
  Route::get('/post/{listing}/pricing', 'PostController@pricing')->name('post.pricing');
  Route::get('/post/{listing}/times', 'PostController@getTimes')->name('post.times');
  Route::post('/post/{listing}/times', 'PostController@postTimes')->name('post.times');

  Route::post('/post/{listing}/uploads', 'PostController@upload')->name('post.upload');
  Route::delete('/post/{listing}/image/{uuid?}', 'PostController@deleteUpload')->name('post.delete-image');


    //Route::resource('listings', 'ListingsController');
});
Route::any('/create/{directory}/message', 'CreateController@postmessage')->name('create.message');

Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('ajax/auth/account', 'Auth\RegisterController@ajaxregister')->name('auth.ajaxaccount');

//TRANSACTION
Route::get('/transaction', 'TransactionController@transaction')->name('transaction');

//search
Route::get('listings/livesearch', 'PostController@SearchEngine')->name('SearchEngine');

