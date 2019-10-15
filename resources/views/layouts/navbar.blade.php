<?php

use App\Http\Controllers;
use App\Http\Controllers\SearchController;

$status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
$status_json = file_get_contents($status_json_url);
$status = json_decode($status_json);
$appname = Config::get('app.name');
sort($status);

$searched_terms = Session::get('terms');
#die(var_dump($searched_terms));
// $selected_pub = Session::get('selected_pub');
// $publications = Session::get('publications');
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
                <button type="submit" style="height:36px; width:36px; padding:0px"><i class="fas fa-search search-button"></i></button>
                <input type="hidden" name="type" id="type" value="{{$searched_terms["type"]}}">
                <input type="hidden" name="archive" id="archive" value="{{$searched_terms["index"]}}">
                <input type="hidden" name="publication" id="publication" value="{{$searched_terms["publication"]}}">
                <input type="hidden" name="sort-by" id="sort-by" value="{{$searched_terms["sort-by"]}}">
                <input type="hidden" name="startdate" id="startdate" value="{{$searched_terms["startdate"]}}">
                <input type="hidden" name="enddate" id="enddate" value="{{$searched_terms["enddate"]}}">
                <input type="hidden" name="size" id="size" value="{{$searched_terms['size']}}">
                {{-- <input type="hidden" name="results-amount" id="results-amount" value="{{$searched_terms["results-amount"]}}"> --}}
                <input type="hidden" name="show-amount" id="show-amount" value="{{Session::get('total_hits')}}">
                <input type="hidden" name="category" id="category" value="">
                {{-- <input type="hidden" name="relevance" id="relevance" value="{{$searched_terms["relevance"]}}"> --}}
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