<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware' => 'auth'], function () use ($router){

    //NCC
    $router->get('/getAllNcc', 'NccController@getAll');
    $router->post('/resultNcc', 'NccController@getDataBykey');
    $router->post('/postNcc', 'NccController@postNcc');
    $router->post('/deleteNcc', 'NccController@deleteNcc');

    //Supplier
    $router->get('/getAllSupplier', 'SuppliersController@getAll');
    $router->post('/resultSupplier', 'SuppliersController@getDataBykey');
    $router->post('/postSupplier', 'SuppliersController@postSupplier');
    $router->post('/deleteSupplier', 'SuppliersController@deleteSupplier');

    //product type
    $router->get('/getAllProductType', 'ProductTypeController@getAll');
    $router->post('/resultProductType', 'ProductTypeController@getDataBykey');
    $router->post('/postProductType', 'ProductTypeController@postProductType');
    $router->post('/deleteProductType', 'ProductTypeController@deleteProductType');

    //product 
    $router->get('/getAllProduct', 'ProductController@getAll');
    $router->post('/resultProduct', 'ProductController@getDataBykey');
    $router->post('/postProduct', 'ProductController@postProduct');
    $router->post('/deleteProduct', 'ProductController@deleteProduct');

     //order
     $router->get('/getAllOrder', 'OrderController@getAll');
     $router->post('/postOrder', 'OrderController@postOrder');
}); 
