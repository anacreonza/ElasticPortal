@extends('layouts.app')
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')
<div class="container">
        @component('results.navlinks')   
        @endcomponent
    <div class="items-container">
        <div class="results-error-block">
            <h1>No results found</h1>
            <p>Your last search returned no results. Perhaps try expanding your search to more publications or a wider date range.</p>
            <a href="/">Search again</a>
        </div>
    </div>
</div>
@endsection