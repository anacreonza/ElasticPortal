<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
    
    
    $current_tab = "document";
    $current_page = $page;
    
    $terms = Session::get('terms');
    $terms['type'] = $current_tab;
    
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['other_docs'];
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
    <div class="">
        @component('results.navlinks')   
        @endcomponent
        @component('results.navtabs')
            @slot('current_page')
                other_docs
            @endslot
            @slot('images')
                {{$hits_count['images']}}
            @endslot
            @slot('stories')
                {{$hits_count['stories']}}
            @endslot
            @slot('pdfs')
                {{$hits_count['pdfs']}}
            @endslot
            @slot('other_docs')
                {{$hits_count['other_docs']}}
            @endslot
        @endcomponent
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
            <table>
                <tr>
                    <td width='200'><span class="item-label">LOID: </span>{{$item['loid']}}</td>
                    {{-- <td width='650'><span class="item-label">Name: </span>{{$item['name']}}</td> --}}
                    {{-- <td width='200'><span class="item-label">Type: </span>{{$item['object_type']}}</td> --}}
                </tr>
            </table>
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