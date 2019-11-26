<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    $current_type = [
        "eom.story",
        "web"
    ];
    $current_page = $page;

    $terms = Session::get('terms');
    $terms['type'] = $current_type;
    
    $output = Session::get('output');
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['stories'];
    $page_offset = $page - 1;
    $terms['from'] = $terms['size'] * $page_offset + 1;
    if($terms['from'] >= $total_items){
        $terms['from'] = $total_items;
    }

    // Remove aggregation terms from query
    $terms['aggs'] = Null;

    Session::put('terms', $terms);

    $results = SearchController::run_search($terms);
    
    $display_array = SearchController::prepare_results($results);

?>
@extends('results.results')
@section('content')
<div class="container">
    <div class="">
        @component('results.navlinks')   
        @endcomponent
        @component('results.navtabs')
            @slot('current_page')
                stories
            @endslot
        @endcomponent
        <div class="items-container">
        @component('results.pagination')
            @slot('current_page')
                stories
            @endslot   
            @slot('current_page_no')
                {{$page}}
            @endslot
        @endcomponent
        @foreach ($display_array as $item)
            <div class="card story-card">
                <div class="card-header">
                    <span class="story-title">{{$item['title']}}</span>
                    <span class="view-link"><a href="/storyviewer/{{$item['loid']}}">View</a></span>
                </div>
                <div class="card-body">
                    @if (isset($item['highlight']['CONTENT.XMLFLAT']))
                        <span class="item-label">Highlights:</span>
                            @foreach ($item['highlight']['CONTENT.XMLFLAT'] as $highlight)
                                ...{!!$highlight!!}...
                            @endforeach
                    @endif
                </div>
                <div class="card-footer">
                    <table class="meta-table">
                        <tr>
                            <td colspan="2" width="400"><span class="item-label">Filename: </span>{{$item['filename']}}</td>
                            <td width="300"><span class="item-label">Archive: </span>{{$item['archive']}}</td>
                            <td width="300"><span class="item-label">Source: </span>{{$item['source']}}</td>
                        </tr>
                        <tr>
                            <td width="150"><span class="item-label">Category: </span>{{$item['category']}}</td>
                            <td><span class="item-label">Keywords: </span>{{$item['keywords']}}</td>
                            <td><span class="item-label">Author: </span>{{$item['author']}}</td>
                            <td><span class="item-label">Publication Date: </span>{{$item['date']}}</td>
                            {{-- <td><span class="item-label">Relevancy Score: </span>{{$item['score']}}</td> --}}
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
        @component('results.pagination')
            @slot('current_page')
                stories
            @endslot   
            @slot('current_page_no')
                {{$page}}
            @endslot
        @endcomponent
    </div>

    </div>
</div>
</div>
@endsection