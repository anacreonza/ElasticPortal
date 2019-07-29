@extends('site')
<?php
#all this crap should probably move into the controller.
$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
$loid = $metadata->_id;
$path = (isset($metadata->_source->SYSTEM->ALERTPATH) ? $metadata->_source->SYSTEM->ALERTPATH : 'Not set');
$filename = (isset($metadata->_source->OBJECTINFO->NAME) ? $metadata->_source->OBJECTINFO->NAME : 'Not set');
# Mappings are inconsistent - sometimes images use ATTRIBUTES->METADATA->PAPER and sometimes ATTRIBUTES->METADATA->GENERAL
$source = (isset($metadata->_source->ATTRIBUTES->METADATA->GENERAL->CUSTOM_SOURCE) ? $metadata->_source->ATTRIBUTES->METADATA->GENERAL->CUSTOM_SOURCE : 'Not set');

# Need to deal with case where there are multiple publicatons.

$newspapers = (isset($metadata->_source->ATTRIBUTES->METADATA->PUBDATA->PAPER->NEWSPAPERS) ? $metadata->_source->ATTRIBUTES->METADATA->PUBDATA->PAPER->NEWSPAPERS: "No publication data.");
$pub_string = "";

if (is_array($newspapers)){
    $pub_array = [];
    # First deal with the object by converting it to an array.
    foreach ($newspapers as $newspaper){
        array_push($pub_array, $newspaper->NEWSPAPER);
    }
    # Remove duplicates
    $pub_array = array_unique($pub_array);
    # Build up pub_string
    foreach ($pub_array as $pub){
        $pub_string .= ", ";
        $pub_string .= $pub;
        $pub_string = ltrim($pub_string, ", ");
    }
} else {
    $pub_string = $newspapers->NEWSPAPER;
}

$keywords = (isset($metadata->_source->ATTRIBUTES->METADATA->GENERAL->DOCKEYWORD) ? $metadata->_source->ATTRIBUTES->METADATA->GENERAL->DOCKEYWORD : 'Not set');
$author = (isset($metadata->_source->ATTRIBUTES->METADATA->GENERAL->DOCAUTHOR) ? $metadata->_source->ATTRIBUTES->METADATA->GENERAL->DOCAUTHOR : 'Not set');
$date = (isset($metadata->_source->ATTRIBUTES->METADATA->GENERAL->DATE_CREATED) ? $metadata->_source->ATTRIBUTES->METADATA->GENERAL->DATE_CREATED : '');
$type = (isset($metadata->_source->ATTRIBUTES->METADATA->PAPER->DOCTYPE) ? $metadata->_source->ATTRIBUTES->METADATA->PAPER->DOCTYPE : '');
$category = (isset($metadata->_source->ATTRIBUTES->METADATA->PAPER->CATEGORY) ? $metadata->_source->ATTRIBUTES->METADATA->PAPER->CATEGORY : '');
$copyright = (isset($metadata->_source->ATTRIBUTES->METADATA->INFOIMAGE->COPYRIGHT_NOTICE) ? $metadata->_source->ATTRIBUTES->METADATA->INFOIMAGE->COPYRIGHT_NOTICE : '');

$pixel_width = (isset($metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->WIDTH) ? $metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->WIDTH : '');
$pixel_height = (isset($metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->HEIGHT) ? $metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->HEIGHT : '');
$colour_space = (isset($metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->COLORTYPE) ? $metadata->_source->SYSATTRIBUTES->PROPS->IMAGEINFO->COLORTYPE : '');
$pub_caption = (isset($metadata->_source->ATTRIBUTES->METADATA->PAPER->PUB_CAPTION) ? $metadata->_source->ATTRIBUTES->METADATA->PAPER->PUB_CAPTION : '');
$custom_caption = (isset($metadata->_source->ATTRIBUTES->METADATA->PAPER->CUSTOM_CAPTION) ? $metadata->_source->ATTRIBUTES->METADATA->PAPER->CUSTOM_CAPTION : '');
?>
@section('header')
    <title>Image Viewer</title>
@endsection
@component('searchbar')
@slot('terms')
    {{$terms['text']}}
@endslot
@slot('selected_archive')
    {{$terms['index']}}
@endslot
@endcomponent
@section('content')
<p><a href="javascript:history.back()">Back to results</a> | <a href="/metadump/{{$loid}}">Raw ElasticSearch data</a></p>
<div class="preview-background">
    <div class="preview-container">
        <img src="http://152.111.25.125:4700/{{$path}}" alt="" class="image-big-preview">
    </div>
    <div class="image-description">
            <div class="image-description-text"><span class="item-label">Filename: </span>{{$filename}}</div>
            <div class="image-description-text"><span class="item-label">Index: </span>{{$index}}</div>
            <div class="image-description-text"><span class="item-label">LOID: </span>{{$loid}}</div>
            <div class="image-description-text"><span class="item-label">Source: </span>{{$source}}</div>
            <div class="image-description-text"><span class="item-label">Publication: </span>{{$pub_string}}</div>
            <div class="image-description-text"><span class="item-label">Author: </span>{{$author}}</div>
            @if ($type)
            <div class="image-description-text"><span class="item-label">Type: </span>{{$type}}</div>
            @endif
            @if ($category)
            <div class="image-description-text"><span class="item-label">Category: </span>{{$category}}</div>
            @endif
            @if ($copyright)
            <div class="image-description-text"><span class="item-label">Copyright: </span>{{$copyright}}</div>
            @endif
            <div class="image-description-text"><span class="item-label">Keywords: </span>{{$keywords}}</div>
            <div class="image-description-text"><span class="item-label">Size: </span>{{$pixel_width}} x {{$pixel_height}}</div>
            @if ($colour_space)
            <div class="image-description-text"><span class="item-label">Colour Space: </span>{{$colour_space}}</div>
            @endif
            @if ($date)
            <div class="image-description-text"><span class="item-label">Date Created: </span>{{$date}}</div>
            @endif
            @if ($pub_caption)
            <div class="image-description-text"><span class="item-label">Pub Caption: </span>{{$pub_caption}}</div>
            @endif
            @if ($custom_caption)
            <div class="image-description-text"><span class="item-label">Custom Caption: </span>{{$custom_caption}}</div>
            @endif
<br>
<a href="http://152.111.25.125:4700{{$path}}">Download <span class="glyphicon glyphicon-download"></span></a>

            
    </div>
</div>
@endsection