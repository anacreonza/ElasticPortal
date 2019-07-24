<?php
    $terms = Session::get('terms');
    $publications = array('All', '');
    $indices = Session::get('indices');
?>
@extends('site')
@section('header')
    <title>Archive | Search</title>
@endsection
@section('content')
    <div class="container">
        <h1>Archive Search</h1>
        <div class="search-background">   
    <form action="/do_advanced_search" method="GET">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="text">Any text:</label>
                    <input style="height:45px" type="text" name="text" id="text" class="form-control" autocomplete="off" autofocus value="{{$terms['text']}}"><br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="archive">Archive:</label>
                    <select name="archive" id="archive" class="form-control">
                        @foreach ($indices as $index)
                            <option value="{{$index->index}}"
                                
                            @if ($index->index == $terms['index'])
                                selected
                            @endif
                            >{{$index->index}}</option>   
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="type">Type: *</label>
                    <select name="type" id="type" class="form-control">
                        <option value="all">All</option>
                        <option value="story">Story</option>
                        <option value="image">Image</option>
                        <option value="pdf">PDF</option>
                        <option value="html">HTML</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="publication">Publication: *</label>
                    <select name="publication" id="publication" class="form-control">
                        <option value="all">All</option>
                        <option value="beeld">Beeld</option>
                        <option value="citypress">City Press</option>
                        <option value="community">Community Papers</option>
                        <option value="dailysun">Daily Sun</option>
                        <option value="dieburger">Die Burger</option>
                        <option value="getty">Getty Images</option>
                        <option value="rapport">Rapport</option>
                        <option value="witness">Witness</option>
                    </select>
                </div>   
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="sort-by">Sort By:</label>
                    <select name="sort-by" id="sort=by" class="form-control">
                        <option value="date">Show newest items first</option>
                        <option value="score" selected>Show most relevant items first</option>
                        <option value="size">Show biggest items first</option>
                    </select>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="startdate">From Date: *</label>
                    <input class="date form-control" type="text" name="startdate" id="startdate" value="">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="enddate">To Date: *</label>
                    <input class="date form-control" type="text" name="enddate" id="enddate" value="">
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="results-amount">Maximum number of results to return:</label>
                    <select name="results-amount" id="results-amount" class="form-control">
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="300">300</option>
                        <option value="500" selected>500</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="show-amount">Number of results to show per page:</label>
                    <select name="show-amount" id="show-amount" class="form-control">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="300">250</option>
                        <option value="500">500</option>
                    </select>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="relevance">Minimum Relevance:</label>
                    <select name="relevance" id="relevance" class="form-control">
                        <option value="14">High (limit search to most relevant items only)</option>
                        <option value="12" selected>Medium</option>
                        <option value="0">Low (show all results)</option>
                    </select>
                </div>
            </div>
        </div>    
        <div class="search-button">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    </div>
</div>
    <div class="container">
        <br>
        <p><b>Note: </b>This site is currently experimental.</p>
        <p>* Not implemented yet</p>
        <a href="/status">Indices Status</a><br>
        <a href="/stats">Stats</a><br>
        <p>Updated 22-Jul-2019 by <a href="mailto:skinnear@media24.com">Stuart Kinnear</a></p>
    </div>
    <script type="text/javascript">
        $('.date').datepicker({  
           format: 'mm-dd-yyyy'
         });  
    </script>
@endsection