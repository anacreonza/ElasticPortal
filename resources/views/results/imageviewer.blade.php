@extends('results.results')
<?php

use \App\Http\Controllers\SearchController;

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

$surrounding_loids = SearchController::get_next_and_previous_loid($metadata['loid']);

?>
@section('header')
    <title>Image Viewer</title>
@endsection
@section('content')
<div class="container">
    @component('results.item-navlinks', ['type' => 'image', 'loid' => $metadata['loid']])
    @endcomponent
    <div class="item-background">
        <div class="content-preview">
            <img src="{{$image_url}}" alt="" class="image-big-preview">
        </div>
        <div>
            @component('results.item-toolbar', ['type' => 'image', 'url' => $image_url, 'surrounding_loids' => $surrounding_loids])         
            @endcomponent
            @component('meta.panel', ['meta' => $metadata])
            @endcomponent
        </div>
    </div>
</div>
@endsection