<?php

Route::group(['prefix'=>'lManager'],function (){
    Route::get('/','Adilchbada\LaravelManager\Http\Controllers\AppManagerController@index');
    Route::post('/exec','Adilchbada\LaravelManager\Http\Controllers\AppManagerController@exec')->name('laravel_manager_exec');
    Route::post('/cmd','Adilchbada\LaravelManager\Http\Controllers\AppManagerController@cmd')->name('cmd');
    Route::get('/clear','Adilchbada\LaravelManager\Http\Controllers\AppManagerController@clear') ;
});

