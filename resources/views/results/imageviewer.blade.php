@extends('results.results')
<?php
$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
$image_url = str_replace(' ', '%20', $metadata['url']);
$dimensions = getimagesize($image_url);

$width = $dimensions[0];
$height = $dimensions[1];

if ($width > $height){
    $orientation = 'landcape';
} else {
    $orientation = 'portrait';
}

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
                <img src="{{$image_url}}" alt="" class="image-big-preview">
                <br>
                <a href="{{$image_url}}">Download <span class="glyphicon glyphicon-download"></span></a>
            </div>
            {{-- Meta Panel Blade Component --}}
            @component('meta.panel', ['meta' => $metadata])
            @endcomponent
    
    </div>
</div>
@endsection