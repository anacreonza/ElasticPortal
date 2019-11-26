<?php
    // Need to do the search from inside the view.
    // Should probably write a helper to do this

    use \App\Http\Controllers\SearchController;
        
    $current_type = ["pdf"];
    $current_page = $page;
    
    $terms = Session::get('terms');
    $terms['type'] = $current_type;
    
    $hits_count = Session::get('item_counts');
    $total_items = $hits_count['pdfs'];
    $page_offset = $page - 1;
    $terms['from'] = $terms['size'] * $page_offset + 1;

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
                pdfs
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
        <div class="items-container">
            @component('results.pagination')
                @slot('current_page')
                    pdfs
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
                            @if(isset($item['publication']))
                            <td width='150'><span class="item-label">Source: </span>{{$item['publication']}}</td>
                            @endif
                            @if(isset($item['section']))
                            <td width='250'><span class="item-label">Section: </span> {{$item['section']}}</td>
                            @endif
                            @if(isset($item['author']))
                            <td width='250'><span class="item-label">Author: </span> {{$item['author']}}</td>
                            @endif
                            @if(isset($item['pubdate']))
                            <td width='250'><span class="item-label">Publication Date: </span> {{$item['pubdate']}}</td>
                            @endif
                        </tr>
                    </table>
                    {{-- <a href="/metadump/{{$display_array[$i]['loid']}}">View metadata</a> --}}
                </div>
            </div>
            @endforeach

            @component('results.pagination')
                @slot('current_page')
                    pdfs
                @endslot   
                @slot('current_page_no')
                    {{$page}}
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endsection