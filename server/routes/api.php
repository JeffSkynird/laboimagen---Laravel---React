<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group([
        'prefix' => 'auth',
    ], function () {
        Route::post('login', 'App\Http\Controllers\v1\Seguridad\AuthController@login');
        Route::post('logout', 'App\Http\Controllers\v1\Seguridad\AuthController@logout')->middleware('auth:api');
    });
    Route::post('users', 'App\Http\Controllers\v1\Seguridad\UsuarioController@create');
    Route::put('user', 'App\Http\Controllers\v1\Seguridad\UsuarioController@updateAuth');
    Route::get('user', 'App\Http\Controllers\v1\Seguridad\UsuarioController@showAuth');
    Route::put('users/{id}', 'App\Http\Controllers\v1\Seguridad\UsuarioController@update');
    Route::get('users/{id}', 'App\Http\Controllers\v1\Seguridad\UsuarioController@show');
    Route::delete('users/{id}', 'App\Http\Controllers\v1\Seguridad\UsuarioController@delete');
    
    Route::get('pacient_orders/{id}', 'App\Http\Controllers\v1\Administracion\OrderController@pacientOrders');

    Route::get('print/{id}', 'App\Http\Controllers\v1\Administracion\ResultController@printPdf');
    Route::get('kpis', 'App\Http\Controllers\v1\Reporte\ReportController@kpis');

    
    Route::middleware('auth:api')->group(function () {
        Route::get('users', 'App\Http\Controllers\v1\Seguridad\UsuarioController@index');

        Route::get('categories', 'App\Http\Controllers\v1\Administracion\CategoryController@index');
        Route::get('categories/{id}', 'App\Http\Controllers\v1\Administracion\CategoryController@show');
        Route::delete('categories/{id}', 'App\Http\Controllers\v1\Administracion\CategoryController@delete');
        Route::post('categories/{id}', 'App\Http\Controllers\v1\Administracion\CategoryController@update');
        Route::post('categories', 'App\Http\Controllers\v1\Administracion\CategoryController@create');
    
        Route::get('value_types', 'App\Http\Controllers\v1\Administracion\ValueTypeController@index');
        Route::delete('value_types/{id}', 'App\Http\Controllers\v1\Administracion\ValueTypeController@delete');
        Route::put('value_types/{id}', 'App\Http\Controllers\v1\Administracion\ValueTypeController@update');
        Route::post('value_types', 'App\Http\Controllers\v1\Administracion\ValueTypeController@create');
    
        Route::get('selector_values', 'App\Http\Controllers\v1\Administracion\SelectorValueController@index');
        Route::delete('selector_values/{id}', 'App\Http\Controllers\v1\Administracion\SelectorValueController@delete');
        Route::put('selector_values/{id}', 'App\Http\Controllers\v1\Administracion\SelectorValueController@update');
        Route::post('selector_values', 'App\Http\Controllers\v1\Administracion\SelectorValueController@create');

        Route::get('pacients', 'App\Http\Controllers\v1\Administracion\PacientController@index');
        Route::delete('pacients/{id}', 'App\Http\Controllers\v1\Administracion\PacientController@delete');
        Route::put('pacients/{id}', 'App\Http\Controllers\v1\Administracion\PacientController@update');
        Route::post('pacients', 'App\Http\Controllers\v1\Administracion\PacientController@create');

        Route::get('orders', 'App\Http\Controllers\v1\Administracion\OrderController@index');
        Route::get('orders/{id}', 'App\Http\Controllers\v1\Administracion\OrderController@show');

        Route::delete('orders/{id}', 'App\Http\Controllers\v1\Administracion\OrderController@delete');
        Route::post('orders/{id}', 'App\Http\Controllers\v1\Administracion\OrderController@update');
        Route::post('orders', 'App\Http\Controllers\v1\Administracion\OrderController@create');

        
        Route::get('exams', 'App\Http\Controllers\v1\Administracion\ExamController@index');
        Route::delete('exams/{id}', 'App\Http\Controllers\v1\Administracion\ExamController@delete');
        Route::post('exams/{id}', 'App\Http\Controllers\v1\Administracion\ExamController@update');
        Route::post('exams', 'App\Http\Controllers\v1\Administracion\ExamController@create');


        Route::post('results', 'App\Http\Controllers\v1\Administracion\ResultController@create');
        Route::get('results/{id}', 'App\Http\Controllers\v1\Administracion\ResultController@show');
        Route::get('order_results/{id}', 'App\Http\Controllers\v1\Administracion\ResultController@showResults');
        Route::get('pacient_results/{id}', 'App\Http\Controllers\v1\Administracion\ResultController@showResultsByPacient');

        

    });
});
