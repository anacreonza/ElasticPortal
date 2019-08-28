<?php
$path = (isset($story->_source->SYSTEM->ALERTPATH) ? $story->_source->SYSTEM->ALERTPATH : 'Not set');
$encodedpath = implode("/", array_map("rawurlencode", explode("/", $path))); # To keep slashes intact
$url = 'http://152.111.25.125:4700' . $encodedpath;
$loid = $story->_id;
$storyxml = file_get_contents($url);
$terms = Session::get('terms');
$query_string = Session::get('query_string');
$filename = (isset($story->_source->OBJECTINFO->NAME) ? $story->_source->OBJECTINFO->NAME : 'Not set');
$index = $query_string['index'];

$author = (isset($story->_source->ATTRIBUTES->METADATA->GENERAL->DOCAUTHOR) ? $story->_source->ATTRIBUTES->METADATA->GENERAL->DOCAUTHOR : 'Not set');
$date = (isset($story->_source->ATTRIBUTES->METADATA->GENERAL->DATE_CREATED) ? $story->_source->ATTRIBUTES->METADATA->GENERAL->DATE_CREATED : '');
$newspapers = (isset($story->_source->ATTRIBUTES->METADATA->PUBDATA->PAPER->NEWSPAPERS) ? $story->_source->ATTRIBUTES->METADATA->PUBDATA->PAPER->NEWSPAPERS: "No publication data.");
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
    if (isset($newspapers->NEWSPAPER)){
        $pub_string = $newspapers->NEWSPAPER;
    } else {
        $pub_string = "Publication metadata not set";
    }
}

$story_obj = new SimpleXMLElement($storyxml);

function prepare_story_html($xml){
    $doc_content = $xml->xpath('story');
    $doc_content = $doc_content[0];
    $html = '';
    if (isset($doc_content->grouphead->headline->ln)){
        $headline = $doc_content->grouphead->headline->ln;
        $html .= '<h2>' . $headline . '</h2>';
    }
    $body_array = $doc_content->text->p;
    if (isset($doc_content->text->byline->author->name)){
        $byline = $doc_content->text->byline->author->name;
        $html .= '<em>' . $byline . '</em>';
    }
    if (isset($body_array)){
        foreach($body_array as $ptag){
            $html .= '<p>' . $ptag . '</p>';
        }
    }
    return $html;
}
$doc_content = prepare_story_html($story_obj);
?>
@extends('site')
@section('header')
    <title>Story Viewer</title>
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

<div class="story-background">
{!!$doc_content!!}
<br>

    <div class="story-description">
        <div class="image-description-text"><span class="item-label">Filename: </span>{{$filename}}</div>
        <div class="image-description-text"><span class="item-label">Index: </span>{{$index}}</div>
        <div class="image-description-text"><span class="item-label">LOID: </span>{{$loid}}</div>
        <div class="image-description-text"><span class="item-label">Publication: </span>{{$pub_string}}</div>
        @if ($date)
        <div class="image-description-text"><span class="item-label">Date Created: </span>{{$date}}</div>
        @endif
        <div class="image-description-text"><span class="item-label">Author: </span>{{$author}}</div>
        <a href="{{$url}}">Link to raw story</a>
    </div>
</div>
@endsection