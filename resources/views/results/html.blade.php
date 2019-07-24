<?php
    $terms = Session::get('terms');
    $output = Session::get('output');
    $items_per_page = Session::get('items_per_page');
    $items_per_page = (int) "$items_per_page";
    $current_page_no = (int) "$page";
    $total_items = $output['counts']['html'];
    $total_items = (float) "$total_items";
    $starting_item = $items_per_page * $current_page_no - $items_per_page;
    $ending_item = $starting_item + $items_per_page;
    $display_array = array_values($output['html']);
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
            html
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
            html
        @endslot   
        @slot('current_page_no')
            {{$current_page_no}}
        @endslot
        @slot('items_count')
            {{$output['counts']['html']}}
        @endslot
        @slot('items_per_page')
            {{$items_per_page}}
        @endslot
        @endcomponent
        @for ($i = $starting_item; $i <= $ending_item; $i++)
        <div class="story-container">
                <div class="story-item">
                    <div class="story-description-text"><span class="item-label">Filename: </span>{{$display_array[$i]['filename']}}</div>
                    <div class="story-description-text"><span class="item-label">Archive: </span>{{$display_array[$i]['archive']}}</div>
                    <div class="story-description-text"><span class="html-headline">{{$display_array[$i]['headline']}}</span></div>
                    <div class="story-description-text">{!!$display_array[$i]['bodypreview']!!}</div>
                    <a href="http://152.111.25.125:4700{{{ $display_array[$i]['path'] }}}">View <span class="glyphicon glyphicon-download"></span></a><br><br>
                </div>
            </div>
        @endfor
        @component('results.pagination')
        @slot('current_page')
            html
        @endslot   
        @slot('current_page_no')
            {{$current_page_no}}
        @endslot
        @slot('items_count')
            {{$output['counts']['html']}}
        @endslot
        @slot('items_per_page')
            {{$items_per_page}}
        @endslot
        @endcomponent
    </div>
</div>
@endsection