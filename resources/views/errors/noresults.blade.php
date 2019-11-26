@extends('layouts.app')
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection
<?php 

?>
@section('content')
<div class="container">
        @component('results.navlinks')   
        @endcomponent
    <div class="items-container">
        <h1>No results found</h1>
        <p>Sorry, your last search returned no results. Perhaps try expanding your search to more publications or a wider date range.</p>
        <a href="/">Search again</a>
        <pre>
            
        </pre>
    </div>
</div>
@endsection