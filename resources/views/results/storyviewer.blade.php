@extends('results.results')
<?php

use \App\Http\Controllers\SearchController;

$terms = Session::get('terms');
$query_string = Session::get('query_string');
$index = $query_string['index'];
$previous_story = "test";
$next_story = "test";

$surrounding_loids = SearchController::get_next_and_previous_loid($story['loid']);

?>
@section('header')
    <title>Story Viewer</title>
@endsection
@section('content')
<div class="container">
    @component('results.item-navlinks', ['type' => 'article', 'loid' => $story['loid']])
    @endcomponent
    <main class="item-background">
        {!!$story['content']!!}
        <aside>
            @component('results.item-toolbar', ['type' => 'article', 'url' => $story['url'], 'surrounding_loids' => $surrounding_loids])         
            @endcomponent
            @component('meta.panel', ['meta' => $story])
            @endcomponent
        </aside>
    </main>
</div>
@endsection