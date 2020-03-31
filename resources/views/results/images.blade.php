<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    $doctypes = Config::get('meta_mappings.doc_types');

    $selected_type = "Images";

    $types = $doctypes[$selected_type];

    $current_page = $page;

    $terms = Session::get('terms');
    $terms['type'] = $selected_type;

    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['images'];
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
                images
            @endslot
        @endcomponent
        <div class="items-container">
            @component('results.pagination')
                @slot('current_page')
                    images
                @endslot   
                @slot('current_page_no')
                    {{$page}}
                @endslot
            @endcomponent
            @foreach($display_array as $item)
                <div class="card image-card">
                    <div class="card-header">{{$item['filename']}}</div>
                    <div class="card-body image-preview-card">
                        <div class="image-preview-container">
                            <a href="/imageviewer/{{$item['loid']}}">
                                @if (\strpos($item['path'], 'pdf'))
                                    <img src="/logos/generic_pdf_icon.png" alt="{{$item['path']}}" name="image" class="image-thumbnail">
                                @else
                                    <img src="http://152.111.25.125:4700{{$item['path']}}?f=image_lowres" alt="{{$item['path']}}" name="image" class="image-thumbnail">
                                @endif
                            </a>
                        </div>
                        <div class="metadata-preview-block">
                            @component('meta.image', ['meta' => $item]);
                            @endcomponent
                        </div>
                    </div>
                </div>
            @endforeach
            @component('results.pagination')
                @slot('current_page')
                    images
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