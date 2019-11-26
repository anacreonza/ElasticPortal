@extends('results.results')
<?php

use \App\Http\Controllers\SearchController;

$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
$previous_story = "test";
$next_story = "test";

SearchController::get_next_and_previous_loid($story['loid']);

?>
@section('header')
    <title>Story Viewer</title>
@endsection
@section('content')
<div class="container">
    <div>
        <a href="/results/stories/1" role="button">Back to results</a> |
        <a href="/metadump/{{$story['loid']}}" role="button">Raw ElasticSearch data</a> |
        <a href="/" role="button">Advanced Search</a>
    </div>
    
    <main class="story-background">
        <div class="story-preview">
            {!!$story['content']!!}
        </div>
        @component('results.item-toolbar', ['type' => 'article', 'url' => $story['url']])         
        @endcomponent
        @component('meta.panel', ['meta' => $story])
        @endcomponent
    </main>
</div>
@endsection