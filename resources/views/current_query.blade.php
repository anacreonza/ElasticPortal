@extends('site')
<?php 

$body = Session::get('query_body');
$index = Session::get('queried_index');
$terms = Session::get('terms');

$query = json_encode($query_string, JSON_PRETTY_PRINT);
$query_body = json_encode($body, JSON_PRETTY_PRINT);
?>
@section('header')
<title>Current Query JSON</title>
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
<p><a href="javascript:history.back()">Back</a> | <a href="/">Advanced Search</a></p>
<h2>Current Query:</h2>
<pre>
GET {{$index}}/_search
{{$query_body}}
</pre>
@endsection