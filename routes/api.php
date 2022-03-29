<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::middleware('throttle:60,1')->prefix('auth')->group(function () {
    //user Authentication
    Route::middleware('auth:api')->get('logout', 'App\Http\Controllers\AuthController@logout');
    Route::get('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::middleware('auth:api')->post('change_password', 'App\Http\Controllers\AuthController@changePassword');
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    // Products
    Route::apiResource('/Products', 'App\Http\Controllers\ProductsController');
    Route::get('/searchProducts', 'App\Http\Controllers\ProductsController@search');
    Route::get('/Products/category/{category_id}', 'App\Http\Controllers\ProductsController@show');
    Route::middleware('auth:api')->get('/user_products', 'App\Http\Controllers\ProductsController@productUser');
    Route::middleware('auth:api')->post('/store_product', 'App\Http\Controllers\ProductsController@store');
    Route::middleware('auth:api')->delete('/delete_product', 'App\Http\Controllers\ProductsController@delete');
    Route::middleware('auth:api')->post('/edit_product', 'App\Http\Controllers\ProductsController@store');
    // favorites product api's
    Route::middleware('auth:api')->post('/store_favorite_item', 'App\Http\Controllers\FavoritesController@store');
    Route::middleware('auth:api')->post('/delete_favorite_item', 'App\Http\Controllers\FavoritesController@delete');
    Route::middleware('auth:api')->apiResource('/favorites_Products', 'App\Http\Controllers\FavoritesController');
//Comments api
    Route::middleware('auth:api')->apiResource('/Product_comments', 'App\Http\Controllers\CommentsController');
    Route::middleware('auth:api')->post('/store_comment', 'App\Http\Controllers\CommentsController@store');
    Route::middleware('auth:api')->delete('/delete_comment', 'App\Http\Controllers\CommentsController@delete');
 // Chat system
    Route::middleware('auth:api')->apiResource('/Conversations', 'App\Http\Controllers\ConversationController');
    Route::middleware('auth:api')->post('/Conversations/store', 'App\Http\Controllers\ConversationController@store');
    Route::middleware('auth:api')->post('/Conversations/read', 'App\Http\Controllers\ConversationController@makConversationAsReaded');
    Route::middleware('auth:api')->post('/send_message', 'App\Http\Controllers\MessageController@store');
    Route::middleware('auth:api')->apiResource('/messages', 'App\Http\Controllers\MessageController');


    // create category
    Route::middleware('auth:api')->post('/addCategory', 'App\Http\Controllers\CategoryController@store');
    Route::get('/allCategories', 'App\Http\Controllers\CategoryController@index');

    // following/follower routes
    Route::middleware('auth:api')->post('/addfollowing', 'App\Http\Controllers\AuthController@storeFollowing');
    Route::middleware('auth:api')->post('/deletefollowing', 'App\Http\Controllers\AuthController@deleteFollowing');
    Route::middleware('auth:api')->get('/getFollowers', 'App\Http\Controllers\AuthController@getFollowers');
    Route::middleware('auth:api')->get('/getFollowing', 'App\Http\Controllers\AuthController@getFollowing');


// profile
    Route::middleware('auth:api')->get('/profile', 'App\Http\Controllers\AuthController@show');




});

