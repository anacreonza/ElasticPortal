@extends('results.results')
<?php 

$body = Session::get('query_body');
$index = Session::get('queried_index');
$terms = Session::get('terms');

$query = json_encode($query_string, JSON_PRETTY_PRINT);
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