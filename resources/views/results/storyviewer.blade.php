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
        <span class="pager-buttons btn-group">
            <a href="/storyviewer/{{Session::get('previous_loid')}}" class="btn btn-outline-primary" role="button">&lt;</a>
            <a href="/storyviewer/{{Session::get('next_loid')}}" class="btn btn-outline-primary" role="button">&gt;</a>
        </span>
    </div>
    
    <main class="story-background">
        <div class="story-preview">
            {!!$story['content']!!}
            @if (isset($story['url']))
            <a href="{{$story['url']}}" target="_blank">Link to raw story</a>
            @endif    
        </div>
        @component('meta.panel', ['meta' => $story])
        @endcomponent
    </main>
</div>
@endsection