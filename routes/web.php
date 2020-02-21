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
use App\http\Controllers\CookieController;

Route::get('/index', function () {
    Article::createIndex($shards = null, $replicas = null);

    Article::putMapping($ignoreConflicts = true);

    Article::addAllToIndex();

    return view('welcome');
});

Route::get('/', 'SearchController@advanced_search_form')->name('root');

Route::get('/do_advanced_search', 'SearchController@do_advanced_search')->name('advanced_search');

Route::get('/search_all', 'SearchController@search_all');

Route::get('/search', 'SearchController@basic_search');

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
    Route::get('other_docs/{page}', function($page){
        return view('results.other_docs')->with('page', $page);
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
    return view('current_query');
});

Route::get('/imageviewer/{loid}', 'SearchController@show_imageviewer');

Route::get('/storyviewer/{loid}', 'SearchController@show_storyviewer');

Route::get('/metadump/{loid}', 'SearchController@meta_dump');

Route::get('/users', 'UserController@index')->middleware('auth');

Route::get('/users/create', 'UserController@create')->middleware('auth');

Route::get('/user/store', 'UserController@store')->middleware('auth');

Route::get('/user/edit/{id}', 'UserController@edit')->middleware('auth');

Route::get('/user/update/{id}', 'UserController@update')->middleware('auth');

Route::get('/user/delete/{id}', 'UserController@destroy')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/adminhome', function(){
    return view('adminhome');
});

Route::get('/changelog', function(){
    return view('admin/changelog');
});

Route::get('/phpinfo', function(){
    return view('user/phpinfo');
});
Route::get('/admin/logs', function(){
    return view('admin/logs');
});
Route::get('/admin/logviewer', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/upload/form', 'UploadController@uploadForm');
Route::post('/upload/files', 'UploadController@uploadSubmit');
Route::get('/clear_cookies', function(){
    CookieController::clear_cookies();
    return redirect('/');
});
