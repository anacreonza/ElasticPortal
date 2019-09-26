<?php
    $terms = Session::get('terms');
    $output = Session::get('output');
    $items_per_page = Session::get('items_per_page');
    $items_per_page = (int) "$items_per_page";
    $current_page_no = (int) "$page";
    $total_items = $output['counts']['stories'];
    $total_items = (float) "$total_items";
    $starting_item = $items_per_page * $current_page_no - $items_per_page;
    $ending_item = $starting_item + $items_per_page;
    $display_array = array_values($output['stories']);
    if ($ending_item > $total_items){
        $ending_item = $total_items;
    }
    $ending_item = $ending_item - 1;
?>
@extends('results.results')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        @component('results.navlinks')   
        @endcomponent
        @component('results.navtabs')
            @slot('current_page')
                stories
            @endslot
            @slot('images')
                {{$output['counts']['images']}}
            @endslot
            @slot('stories')
                {{$output['counts']['stories']}}
            @endslot
            @slot('pdfs')
                {{$output['counts']['pdfs']}}
            @endslot
            @slot('html')
                {{$output['counts']['html']}}
            @endslot
        @endcomponent
        @component('results.pagination')
            @slot('current_page')
                stories
            @endslot   
            @slot('current_page_no')
                {{$current_page_no}}
            @endslot
            @slot('items_count')
                {{$total_items}}
            @endslot
            @slot('items_per_page')
                {{$items_per_page}}
            @endslot
        @endcomponent
        @for ($i = $starting_item; $i < $ending_item; $i++)
            <div class="card story-card">
                <div class="card-header">
                    <span class="story-title">{{$display_array[$i]['title']}}</span>
                    <span class="view-link"><a href="/storyviewer/{{$display_array[$i]['loid']}}">View</a></span>
                </div>
                <div class="card-body">
                    @if (isset($display_array[$i]['highlight']['CONTENT.XMLFLAT']))
                        <span class="item-label">Highlights:</span>
                            @foreach ($display_array[$i]['highlight']['CONTENT.XMLFLAT'] as $highlight)
                                ...{!!$highlight!!}...
                            @endforeach
                    @endif
                </div>
                <div class="card-footer">
                    <table class="meta-table">
                        <tr>
                            <td colspan="2" width="400"><span class="item-label">Filename: </span>{{$display_array[$i]['filename']}}</td>
                            <td width="300"><span class="item-label">Archive: </span>{{$display_array[$i]['archive']}}</td>
                            <td width="300"><span class="item-label">Source: </span>{{$display_array[$i]['source']}}</td>
                        </tr>
                        <tr>
                            <td width="150"><span class="item-label">Category: </span>{{$display_array[$i]['category']}}</td>
                            <td><span class="item-label">Keywords: </span>{{$display_array[$i]['keywords']}}</td>
                            <td><span class="item-label">Author: </span>{{$display_array[$i]['author']}}</td>
                            <td><span class="item-label">Publication Date: </span>{{$display_array[$i]['date']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endfor
        <br>
        @component('results.pagination')
        @slot('current_page')
            stories
        @endslot   
        @slot('current_page_no')
            {{$current_page_no}}
        @endslot
        @slot('items_count')
            {{$output['counts']['stories']}}
        @endslot
        @slot('items_per_page')
            {{$items_per_page}}
        @endslot
    @endcomponent
    </div>
</div>
</div>
@endsection