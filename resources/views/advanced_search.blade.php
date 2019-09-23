<?php
    $terms = Session::get('terms');
    $indices = $data['indices'];
    $publications = $data['publications'];
    $categories = $data['categories'];
    $types = $data['types'];
    $selected_type = Session::get('type');
    $selected_pub = Session::get('selected_pub');
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
        <div class="row justify-content-center">
        {{-- <h1>Media24 Newspaper Archive Search</h1> --}}
        <div class="card">
            <div class="card-header">Advanced Search</div>
            <div class="card-body">
                <form action="/do_advanced_search" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input style="height:45px" type="text" name="text" id="text" class="form-control" autocomplete="off" autofocus value="{{$terms['text']}}"><br>
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
                                    @if ($type == $terms['type'])
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
                                        <option value={{$category['name']}}>{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="author">Author:*</label>
                                <input type="text" name="author" id="author" class="form-control" autocomplete="off" value=""><br>
                            </div>
                        </div>
                    </div>        
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="startdate">From Date:</label>
                                <input class="form-control" data-provide="datepicker" type="text" name="startdate" id="startdate" value="{{$selected_startdate}}">
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
                                <input class="form-control" data-provide="datepicker" type="text" name="enddate" id="enddate" value="{{$selected_enddate}}">
                            {{-- JQuery script for date picker --}}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                 $('#enddate').datepicker({
                                  "format": "yyyy-mm-dd",
                                  "keyboardNavigation": true
                                 }); 
                                });
                                </script>                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="results-amount">Maximum number of results to return:</label>
                                <select name="results-amount" id="results-amount" class="form-control">
                                    <option value="10" 
                                    @if ($selected_maxresults == 10)
                                        selected
                                    @endif
                                    >10</option>
                                    <option value="50" 
                                    @if ($selected_maxresults == 50)
                                        selected
                                    @endif
                                    >50</option>
                                    <option value="100" 
                                    @if ($selected_maxresults == 100)
                                        selected
                                    @endif
                                    >100</option>                        
                                    <option value="300" 
                                    @if ($selected_maxresults == 300)
                                        selected
                                    @endif
                                    >300</option>
                                    <option value="500" 
                                    @if ($selected_maxresults == 500)
                                        selected
                                    @elseif (! isset($selected_maxresults))
                                        selected
                                    @endif
                                    >500</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="show-amount">Number of results to show per page:</label>
                                <select name="show-amount" id="show-amount" class="form-control">
                                    <option value="30" 
                                    @if ($selected_maxperpage == 30)
                                        selected
                                    @endif
                                    >30</option>
                                    <option value="50" 
                                    @if ($selected_maxperpage == 50)
                                        selected
                                    @endif
                                    >50</option>
                                    <option value="100" 
                                    @if ($selected_maxperpage == 100)
                                        selected
                                    @endif
                                    >100</option>
                                    <option value="500" 
                                    @if ($selected_maxperpage == 500)
                                        selected
                                    @endif
                                    >500</option>
                                </select>
                            </div>
                        </div>
                    </div>    
            
                    <div class="search-button">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p>Recent changes:</p>
                <ul>
                    <li>Entirely new look.</li>
                    <li>Added user login ability. Registered users can access the admin functions like stats, Kibana and the dashboard.</li>
                    <li>Added new metadata viewing block to images.</li>
                    <li>Fixed crash when viewing older stories with invalid XML tag in header.</li>
                    <li>Removed quick search in navigation bar. It will return once I've figured out how to wrangle the CSS.</li>
                </ul>
                <p>* Not implemented yet</p>
                <p>Last updated 23-Sep-2019 by <a href="mailto:skinnear@media24.com">Stuart Kinnear</a></p>
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