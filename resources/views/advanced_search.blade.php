<?php
    use \App\Http\Controllers\UserController;

    $user_prefs = UserController::get_user_prefs();

    $terms = Session::get('terms');
    $indices = $data['indices'];
    $publications = $data['publications'];
    $categories = $data['categories'];
    $types = $data['types'];
    $authors = $data['authors'];
    $selected_type = Session::get('selected_type');
    $selected_pub = Session::get('selected_pub');
    $selected_category = Session::get('selected_category');
    $selected_sorting = Session::get('sorting');
    $selected_maxresults = Session::get('maxresults');
    $selected_maxperpage = Session::get('maxperpage');
    $selected_minrelevance = Session::get('minrelevance');
    $selected_author = Session::get('author');
    $selected_match_option = Session::get('match');
    if (!isset($selected_match_option)){
        $selected_match_option = 'allwords';
    }
    $selected_startdate = Session::get('selected_startdate');
    $selected_enddate = Session::get('selected_enddate');
?>
@extends('layouts.app')
@section('header')
    <title>Archive | Search</title>
    <script>
        $('.datepicker').datepicker({
        format: 'yyy-mm-dd',
        startDate: '-15y'
        });
    </script>
@endsection

@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        {{-- <h1>Media24 Newspaper Archive Search</h1> --}}
        <div class="search-card">
            <div class="card-header">Advanced Search</div>
            <div class="card-body">
                <form action="/do_advanced_search" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input style="height:45px" type="text" name="text" id="text" class="form-control search-form" autocomplete="off" autofocus value="{{$terms['text']}}"><br>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input"  name="match" value="allwords" @if ($selected_match_option == 'allwords')checked @endif>Match Exact Words
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input"  name="match" value="phrase" @if ($selected_match_option == 'phrase')checked @endif>Match Exact Phrase
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="radio" class="form-check-input"  name="match" value="any" @if ($selected_match_option == 'any')checked @endif>Match Any Text
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Filters:</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="archive">Archive:</label>
                                <select name="archive" id="archive" class="form-control">
                                    @foreach ($indices as $index)
                                        <option value="{{$index}}"
                                        @if (!isset($terms['index']))
                                            @if ($index == 'published@methcarch_eomjse11_arch')
                                            selected
                                            @endif
                                        @endif
                                        @if ($index == $terms['index'])
                                            selected
                                        @endif
                                        >{{$index}}</option>   
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Type:</label>
                                <select name="type" id="type" class="form-control">
                                    @foreach ($types as $type)
                                    <option value="{{$type}}"
                                    @if ($type == $selected_type)
                                        selected
                                    @endif
                                    >{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="publication">Publication:</label>
                                <select name="publication" id="publication" class="form-control">
                                    @foreach ($publications as $publication)
                                    <option value={{$publication}}
                                    @if ($publication == $selected_pub)
                                        selected
                                    @endif    
                                    >{{$publication}}</option>                            
                                    @endforeach
                                </select>
                            </div>   
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort-by">Sort By:</label>
                                <select name="sort-by" id="sort=by" class="form-control">
                                    <option value="date">Date (show newest items first)</option>
                                    <option value="score" selected>Score (show most relevant items first)</option>
                                    <option value="size">Size (show biggest items first)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="category">Category:</label>
                                <select name="category" id="category" class="form-control">
                                    @foreach ($categories as $category)
                                        <option value={{$category}}
                                        @if ($category == $selected_category)
                                            selected
                                        @endif
                                        >{{$category}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="author">Author:*</label>
                                <input type="text" name="author" id="author" class="form-control" autocomplete="off" value="">
                            </div>
                        </div>
                    </div>        
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="startdate">From Date:</label>
                                <input class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" type="text" name="startdate" id="startdate" value="{{$selected_startdate}}">
                            </div>
                            {{-- JQuery script for date picker --}}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                 $('#startdate').datepicker({
                                  "format": "yyyy-mm-dd",
                                  "keyboardNavigation": true
                                 }); 
                                });
                                </script>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="enddate">To Date:</label>
                                <input class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd" type="text" name="enddate" id="enddate" value="{{$selected_enddate}}">
                            {{-- JQuery script for date picker --}}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                 $('#enddate').datepicker({
                                  "format": "yyyy-mm-dd",
                                  "keyboardNavigation": true
                                 }); 
                                });
                                </script>
                            </div>
                            <input type="hidden" name="size" id="size" value="{{$user_prefs->results_per_page}}">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="search-button"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p>* Not implemented yet</p>
                <p>Please note this site is still experimental and may be unstable.</p>
                <p>Last updated 9-Dec-2019 by <a href="mailto:skinnear@media24.com">Stuart Kinnear</a></p>
                <a href="/changelog">Change Log</a>
            </div>
        </div>
 </div>

    <script type="text/javascript">
        $('.date').datepicker({  
           format: 'mm-dd-yyyy'
         });  
    </script>
</div>
@endsection