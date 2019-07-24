<?php
    $terms = Session::get('terms');
    $output = Session::get('output');
    $items_per_page = Session::get('items_per_page');
    $items_per_page = (int) "$items_per_page";
    $current_page_no = (int) "$page";
    $total_items = $output['counts']['images'];
    $total_items = (float) "$total_items";
    $starting_item = $items_per_page * $current_page_no - $items_per_page;
    $ending_item = $starting_item + $items_per_page;
    $display_array = array_values($output['images']);
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
            images
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
        <div class="images-background">
            @component('results.pagination')
                @slot('current_page')
                    images
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
            <div class="images-container">
            @for ($i = $starting_item; $i <= $ending_item; $i++)
                <div class="image-container">
                        <div class="image-item">
                            <a href="/imageviewer/{{$display_array[$i]['loid']}}">
                                <img src="http://152.111.25.125:4700{{$display_array[$i]['path']}}?f=image_lowres" alt="{{$display_array[$i]['path']}}" height="220px" class="image-preview" name="image">
                            </a>
                        </div>
                    </div>
            @endfor
            </div>
            @component('results.pagination')
            @slot('current_page')
                images
            @endslot   
            @slot('current_page_no')
                {{$current_page_no}}
            @endslot
            @slot('items_count')
                {{$output['counts']['images']}}
            @endslot
            @slot('items_per_page')
                {{$items_per_page}}
            @endslot
        @endcomponent
        </div>
    </div>
@endsection