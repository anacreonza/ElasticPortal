@extends('site')
@section('header')
    <title>Archive | Results</title>
@endsection
@section('content')
<p><a href="/current_query">Current Query JSON</a><span> | </span><a href="/test">Current Raw Results</a><span> | </span><a href="/">Advanced search</a></p>
<div class="flex-container">
<p>No results found</p>
<p>Last request:</p>
<pre>
    <?php
    $json = json_encode($data, JSON_PRETTY_PRINT);
    ?>
{{$json}}
</pre>
</div>
@endsection