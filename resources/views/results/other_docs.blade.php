<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    
    $current_types = ["document"];
    $current_page = $page;
    
    $terms = Session::get('terms');
    $terms['type'] = $current_types;
    
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['other_docs'];
    $page_offset = $page - 1;
    $terms['from'] = $terms['size'] * $page_offset + 1;

    // Remove aggregation terms from query
    $terms['aggs'] = Null;

    $results = SearchController::run_search($terms);

    $display_array = SearchController::prepare_results($results);
    
?>
@extends('results.results')
@section('header')
    <title>Archive | Results</title>
@endsection
@section('content')
<div class="container">
    <div class="">
        @component('results.navlinks')   
        @endcomponent
        @component('results.navtabs')
            @slot('current_page')
                other_docs
            @endslot
        @endcomponent
        <div class="items-container">
            @component('results.pagination')
                @slot('current_page')
                    other_docs
                @endslot   
                @slot('current_page_no')
                    {{$page}}
                @endslot
            @endcomponent
            @foreach ($display_array as $item)
            <div class="card story-card">
                <div class="card-header">
                    {{$item['filename']}}
                    <span class="view-link"><a href="http://152.111.25.125:4700{{$item['path']}}">View</a></span>
                </div>
                <div class="card-body">
                    @if (isset($item['preview']))
                    <div class="text-preview">{!!$item['preview']!!}</div>                        
                    @endif
                    <div class="details" style="display: flex; justify-content: space-between;">
                        <div><span class="item-label">LOID: </span>{{$item['loid']}}</div>
                        <div><span class="item-label">Score: </span>{{$item['score']}}</div>
                        @if(isset($item['publication']))
                        <div><span class="item-label">Source: </span>{{$item['publication']}}</div>
                        @endif
                        @if(isset($item['section']))
                        <div><span class="item-label">Section: </span> {{$item['section']}}</div>
                        @endif
                        @if(isset($item['author']))
                        <div><span class="item-label">Author: </span> {{$item['author']}}</div>
                        @endif
                        @if(isset($item['pubdate']))
                        <div><span class="item-label">Publication Date: </span> {{$item['pubdate']}}</div>
                        @endif
                    </div>
                    {{-- <a href="/metadump/{{$display_array[$i]['loid']}}">View metadata</a> --}}
                </div>
            </div>
            @endforeach
            @component('results.pagination')
                @slot('current_page')
                    other_docs
                @endslot   
                @slot('current_page_no')
                    {{$page}}
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endsection