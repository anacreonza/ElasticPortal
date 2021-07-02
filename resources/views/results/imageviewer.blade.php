@extends('results.results')
@if (!session('terms'))
    @php
        return redirect('root')->with('errors', "Session expired!");
    @endphp
@endif
<?php
use \App\Http\Controllers\SearchController;

$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
$dimensions = getimagesize($preview);

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
            {{-- <img src="{{$image}}?f=image_lowres" alt="" class="image-big-preview"> --}}
            <img src="{{$preview}}" alt="" class="image-big-preview">
        </div>
        <div>
            @component('meta.panel', ['meta' => $metadata])
            @endcomponent
        </div>
    </div>
</div>
@endsection