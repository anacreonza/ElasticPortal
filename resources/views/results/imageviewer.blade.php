@extends('results.results')
<?php
$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
?>
@section('header')
    <title>Image Viewer</title>
@endsection
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back to results</a> | <a href="/metadump/{{$metadata['loid']}}">Raw ElasticSearch data</a></p>
    <div class="story-background">
    {{-- <div class="preview-background"> --}}
            <div class="story-preview">
                <img src="{{$metadata['url']}}" alt="" class="image-big-preview">
                <br>
                <a href="{{$metadata['url']}}">Download <span class="glyphicon glyphicon-download"></span></a>
            </div>
            {{-- This should be a blade component --}}
            @if (isset($metadata['category']))
            <div class="image-description-text"><span class="item-label">Category: </span>{{$metadata['category']}}</div>
            @endif
            @if (isset($metadata['type']))
            <div class="image-description-text"><span class="item-label">Type: </span>{{$metadata['type']}}</div>
            @endif
            @component('meta.panel', ['meta' => $metadata])
            @endcomponent
    
    </div>
</div>
@endsection