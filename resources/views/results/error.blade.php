<?php
    $terms = Session::get('terms');
    $output = Session::get('output');
    $items_per_page = Session::get('items_per_page');
    $items_per_page = (int) "$items_per_page";
    $current_page_no = 0;
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
    <div class="images-background">
    <div class="error">
        <h2>No results found</h2>
    </div>
    <div class="search-background">
        <p>Last request:</p>
        <pre>
            <?php
            $json = json_encode($data, JSON_PRETTY_PRINT);
            ?>
        {{$json}}
        </pre>
    </div>
</div>
</div>
@endsection