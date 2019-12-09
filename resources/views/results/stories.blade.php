<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    $current_type = [
        "eom.story"
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
                    @if (isset($item['title']))
                        <span class="story-title">{{$item['title']}}</span>
                    @endif
                    @if (isset($item['loid']))
                        <span class="view-link"><a href="/storyviewer/{{$item['loid']}}">View</a></span>
                    @endif
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
                    <div class="meta-footer">
                        {{-- <div class="meta-item"><span class="item-label">Filename: </span>{{$item['filename']}}</div> --}}
                        {{-- <div class="meta-item"><span class="item-label">LOID: </span>{{$item['loid']}}</div> --}}
                        {{-- <div class="meta-item"><span class="item-label">Archive: </span>{{$item['archive']}}</div> --}}
                        @if (isset($item['source']))
                        <div class="meta-item"><span class="item-label">Source: </span>{{$item['source']}}</div>
                        @endif
                        @if (isset($item['category']))
                        {{-- <div class="meta-item"><span class="item-label">Category: </span>{{$item['category']}}</div> --}}
                        @endif
                        @if (isset($item['keywords']))
                        <div class="meta-item"><span class="item-label">Keywords: </span>{{$item['keywords']}}</div>
                        @endif
                        @if (isset($item['author']))
                        <div class="meta-item"><span class="item-label">Author: </span>{{$item['author']}}</div>
                        @endif
                        @if (isset($item['date']))
                        <div class="meta-item"><span class="item-label">Date: </span>{{$item['date']}}</div>
                        @endif
                        <div class="meta-item"><span class="item-label">Score: </span>{{$item['score']}}</div>
                    </div>
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