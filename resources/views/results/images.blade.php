<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    $current_type = "image"; // rather use OBJECTINFO.TYPE: image its more general
    $current_page = $page;

    $terms = Session::get('terms');
    $terms['type'] = $current_type;

    $output = Session::get('output');
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['images'];
    $page_offset = $page - 1;
    $terms['from'] = $terms['size'] * $page_offset + 1;

    $results = SearchController::run_search($terms);

    $display_array = SearchController::prepare_results($results);
    
?>
@extends('results.results')
@section('header')
    <title>Archive | Results</title>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @component('results.navlinks')   
        @endcomponent
        @component('results.navtabs')
            @slot('current_page')
                images
            @endslot
        @endcomponent
        @component('results.pagination')
            @slot('current_page')
                images
            @endslot   
            @slot('current_page_no')
                {{$page}}
            @endslot
        @endcomponent
            <div class="images-container">
            @foreach($display_array as $item)
                <div class="image-container">
                    <div class="image-item">
                        <a href="/imageviewer/{{$item['loid']}}">
                            <img src="http://152.111.25.125:4700{{$item['path']}}?f=image_lowres" alt="{{$item['path']}}" height="220px" class="image-preview" name="image">
                        </a>
                    </div>
                </div>
            @endforeach
            </div>
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
@endsection