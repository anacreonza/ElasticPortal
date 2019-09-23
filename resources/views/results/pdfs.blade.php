<?php
    $terms = Session::get('terms');
    $output = Session::get('output');
    $items_per_page = Session::get('items_per_page');
    $items_per_page = (int) "$items_per_page";
    $current_page_no = (int) "$page";
    $total_items = $output['counts']['pdfs'];
    $total_items = (float) "$total_items";
    $starting_item = $items_per_page * $current_page_no - $items_per_page;
    $ending_item = $starting_item + $items_per_page;
    $display_array = array_values($output['pdfs']);
    if ($ending_item > $total_items){
        $ending_item = $total_items;
    }
    $ending_item = $ending_item - 1;
?>
@extends('layouts.app')
@section('content')
    <div class="container">
    <p><a href="/current_query">Current Query JSON</a><span> | </span><a href="/test">Current Raw Results</a><span> | </span><a href="/">Advanced search</a></p>
        @component('results.navtabs')
        @slot('current_page')
            pdfs
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
            pdfs
        @endslot   
        @slot('current_page_no')
            {{$current_page_no}}
        @endslot
        @slot('items_count')
            {{$output['counts']['pdfs']}}
        @endslot
        @slot('items_per_page')
            {{$items_per_page}}
        @endslot
        @endcomponent
        @for ($i = $starting_item; $i <= $ending_item; $i++)
        <div class="story-container">
            <div class="story-item">
                <div class="story-description-text"><span class="item-label">Filename: </span>{{$display_array[$i]['filename']}}</div>
                <div class="story-description-text"><span class="item-label">Keywords: </span>{{$display_array[$i]['keywords']}}</div>
                <a href="http://152.111.25.125:4700{{$display_array[$i]['path']}}">Download <span class="glyphicon glyphicon-download"></span></a><br><br>
            </div>
        </div>
        @endfor
        @component('results.pagination')
        @slot('current_page')
            pdfs
        @endslot   
        @slot('current_page_no')
            {{$current_page_no}}
        @endslot
        @slot('items_count')
            {{$output['counts']['pdfs']}}
        @endslot
        @slot('items_per_page')
            {{$items_per_page}}
        @endslot
        @endcomponent
   </div>
</div>
@endsection