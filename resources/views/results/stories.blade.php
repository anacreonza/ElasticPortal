<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    $doctypes = Config::get('meta_mappings.doc_types');

    $selected_type = "Articles";

    $types = $doctypes[$selected_type];

    $current_page = $page;

    $terms = Session::get('terms');
    $terms['type'] = $selected_type;
    
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['stories'];
    $page_offset = $page - 1;
    $terms['from'] = $terms['size'] * $page_offset;
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
                    <a href="/storyviewer/{{$item['loid']}}"><span class="story-title">{{$item['title']}}</span></a>
                    @else
                    <a href="/storyviewer/{{$item['loid']}}"><span class="story-title">No headline found in story</span></a>
                    @endif
                </div>
                <div class="card-body story-preview-card">
                    @if (isset($item['highlight']))
                    <div class="story-preview-container">
                        @if (isset($item['highlight']['CONTENT.XMLFLAT']))
                            <span class="item-label">Highlights:</span>
                                @foreach ($item['highlight']['CONTENT.XMLFLAT'] as $highlight)
                                    ...{!!$highlight!!}...
                                @endforeach
                        @endif
                    </div>
                    @endif
                    <div class="metadata-preview-block-singlecolumn">
                        @component('meta.story', ['meta' => $item])
                        @endcomponent                    
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