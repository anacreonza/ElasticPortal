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
@extends('results.results')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
        @component('results.navlinks')   
        @endcomponent
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
        <div class="card story-card">
            <div class="card-header">
                {{$display_array[$i]['filename']}}
                <span class="view-link"><a href="http://152.111.25.125:4700{{$display_array[$i]['path']}}">View</a></span>
            </div>
            <div class="card-body">
                <table>
                    <tr>
                        <td width='200'><span class="item-label">LOID: </span>{{$display_array[$i]['loid']}}</td>
                        <td width='150'><span class="item-label">Source: </span>{{$display_array[$i]['publication']}}</td>
                        <td width='250'><span class="item-label">Section: </span> {{$display_array[$i]['section']}}</td>
                        <td width='250'><span class="item-label">Author: </span> {{$display_array[$i]['author']}}</td>
                        <td width='250'><span class="item-label">Publication Date: </span> {{$display_array[$i]['pubdate']}}</td>
                    </tr>
                </table>
                {{-- <a href="/metadump/{{$display_array[$i]['loid']}}">View metadata</a> --}}
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