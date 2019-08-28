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
@section('header')
    <title>Archive | Results</title>
@endsection
@section('content')
<p><a href="/current_query">Current Query JSON</a><span> | </span><a href="/test">Current Raw Results</a><span> | </span><a href="/">Advanced search</a></p>
    <div class="flex-container">
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
        <div class="story-background">
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
        <div class="story-container">
                <div class="story-item">
                    <table class="table">
                        <tr>
                            <td colspan="4" class="story-description-title"><span class="item-label">Title: </span>{{$display_array[$i]['title']}}</td>
                        </tr>
                        <tr>
                            <td class="story-description-text-col1"><span class="item-label">Filename: </span>{{$display_array[$i]['filename']}}</td>
                            <td class="story-description-text-col2"><span class="item-label">Archive: </span>{{$display_array[$i]['archive']}}</td>
                            <td class="story-description-text-col2"><span class="item-label">Source: </span>{{$display_array[$i]['source']}}</td>
                            <td class="story-description-text-col2"><span class="item-label">Category: </span>{{$display_array[$i]['category']}}</td>
                        </tr>
                        @if (isset($display_array[$i]['highlight']['CONTENT.XMLFLAT']))
                            <tr><td class="story-description-text" colspan="4">
                            <span class="item-label">Highlights:</span>
                                @foreach ($display_array[$i]['highlight']['CONTENT.XMLFLAT'] as $highlight)
                                    ...{!!$highlight!!}...
                                @endforeach
                        @endif
                        </td></tr>
                        <tr>
                            <td class="story-description-text"><span class="item-label">Keywords: </span>{{$display_array[$i]['keywords']}}</td>
                            <td class="story-description-text"><span class="item-label">Author: </span>{{$display_array[$i]['author']}}</td>
                            <td class="story-description-text"><span class="item-label">Date Published: </span>{{$display_array[$i]['date']}}</td>
                            <td class="story-description-text"><a href="/storyviewer/{{$display_array[$i]['loid']}}">View</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        @endfor
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
@endsection