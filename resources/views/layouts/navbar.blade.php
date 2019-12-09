<?php

use App\Http\Controllers;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CookieController;
CookieController::initialise_cookie();
if (isset($_COOKIE['user_prefs'])){
    $user_prefs = \json_decode($_COOKIE['user_prefs']);
}

$status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
$status_json = file_get_contents($status_json_url);
$status = json_decode($status_json);
$appname = Config::get('app.name');
sort($status);

$searched_terms = Session::get('terms');

// Deal with empty search terms to prevent the no results page crashing the navbar.
if (!isset($searched_terms["type"])){
    $searched_terms["type"] = "All";
}
if (!isset($searched_terms["index"])){
    $searched_terms["index"] = Session::get('indices')[0];
}
if (!isset($searched_terms["publication"])){
    $searched_terms["publication"] = "All";
}

?>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{$appname}}
        </a>
        @if(isset($enable_search) && $enable_search == "yes")
        <div class="search-container" style="padding-left:30px">
            <form action="/do_advanced_search" method="GET" style="display: flex;">
                <input class="search-field" style="height:36px; width:550px; font-size:14pt;" type="text" name="text" placeholder=" Search" autofocus value="{{$searched_terms['text']}}">
                <button type="submit" class="search-button-mini"><i class="fa fa-search" aria-hidden="true"></i></button>
                <input type="hidden" name="type" id="type" value="{{serialize($searched_terms["type"])}}">
                <input type="hidden" name="archive" id="archive" value="{{$searched_terms["index"]}}">
                <input type="hidden" name="publication" id="publication" value="{{$searched_terms["publication"]}}">
                <input type="hidden" name="sort-by" id="sort-by" value="{{$searched_terms["sort-by"]}}">
                <input type="hidden" name="startdate" id="startdate" value="{{$searched_terms["startdate"]}}">
                <input type="hidden" name="enddate" id="enddate" value="{{$searched_terms["enddate"]}}">
                <input type="hidden" name="size" id="size" value="{{$user_prefs->results_per_page}}">
                <input type="hidden" name="show-amount" id="show-amount" value="{{Session::get('total_hits')}}">
                <input type="hidden" name="category" id="category" value="">
                <input type="hidden" name="author" id="author" value="{{$searched_terms["author"]}}">
                <input type="hidden" name="match" id="match" value="{{$searched_terms["match"]}}">
            </form>
        </div>
        @endif
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="/upload/form" class="dropdown-item">Upload Content</a>
                            <a href="/home" class="dropdown-item">Admin Page</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>