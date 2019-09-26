@extends('results.results')
<?php
$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
?>
@section('header')
    <title>Story Viewer</title>
@endsection
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back to results</a> | <a href="/metadump/{{$story['loid']}}">Raw ElasticSearch data</a> | <a href="/">Advanced Search</a></p>
    
    <div class="story-background">
        <div class="story-preview">
            {!!$story['content']!!}
            @if (isset($story['url']))
            <a href="{{$story['url']}}">Link to raw story</a>
            @endif    
        </div>
        @component('meta.panel', ['meta' => $story])
        @endcomponent
    </div>
</div>
@endsection