@extends('layouts.app')
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection
<?php 

$query_string = Session::get("query_string");

?>
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back</a> | <a href="/">Advanced Search</a></p>
    <h1>No results found</h1>
    <h4>Last Query:</h4>
    <pre>
    GET {{$query_string['index']}}/_search
    {{json_encode($query_string['body'], JSON_PRETTY_PRINT)}}
    </pre>
</div>
@endsection