@extends('results.results')
<?php 

$query_string = Session::get("query_string");

$body = $query_string['body'];

$index = $query_string['index'];

$query_body = json_encode($body, JSON_PRETTY_PRINT);

?>
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back</a> | <a href="/">Advanced Search</a></p>
    <h2>Current Query:</h2>
    <pre>
    GET {{$index}}/_search
    {{$query_body}}
    </pre>
</div>
@endsection