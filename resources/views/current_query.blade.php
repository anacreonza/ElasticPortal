@extends('results.results')
<?php 

$query_string = Session::get("query_string");

if (! isset($query_string)){
    $query_string['body'] = "User has not yet run a query.";
}

$body = $query_string['body'];

$query_body = json_encode($body, JSON_PRETTY_PRINT);

?>
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back</a> | <a href="/">Advanced Search</a></p>
    <div class="items-container">
        <h2>Current Query:</h2>
        @if (isset($query_string['index']))
            <pre>GET {{$query_string['index']}}/_search</pre>
        @endif
        <pre>{{$query_body}}</pre>
    </div>
</div>
@endsection