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

use App\Article;
use App\http\Controllers\SearchController;

Route::get('/index', function () {
    Article::createIndex($shards = null, $replicas = null);

    Article::putMapping($ignoreConflicts = true);

    Article::addAllToIndex();

    return view('welcome');
});

Route::get('/', 'SearchController@advanced_search_form');

Route::get('/do_advanced_search', 'SearchController@do_advanced_search');

Route::get('/search_all', 'SearchController@search_all');

Route::prefix('/results')->group(function(){
    Route::get('images/{page}', function($page){
        return view('results.images')->with('page', $page);
    });
    Route::get('stories/{page}', function($page){
        return view('results.stories')->with('page', $page);
    });
    Route::get('pdfs/{page}', function($page){
        return view('results.pdfs')->with('page', $page);
    });
    Route::get('html/{page}', function($page){
        return view('results.html')->with('page', $page);
    });
});

Route::get('/test', function(){
    $results = Session::get('results');
    return dd($results);
});

Route::get('/status', function(){
    return view('status');
});
Route::get('/stats', 'SearchController@get_stats');

Route::get('/current_query', function(){
    $query_string = Session::get('query_string');
    return view('current_query')->with('query_string', $query_string);
});

Route::get('/imageviewer/{loid}', 'SearchController@show_imageviewer');

Route::get('/metadump/{loid}', 'SearchController@meta_dump');