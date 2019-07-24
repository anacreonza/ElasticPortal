@extends('site')
@section('header')
    <title>Search Portal</title>
@endsection
@section('content')
    <h1 class="jumbotron">Elasticsearch Portal</h1>
    <form action="/search_all" method="GET">
        <div class="form-group">
            <input style="height:50px" type="text" name="terms" id="terms" class="form-control" autocomplete="off" autofocus><br>
            <div class="search-button">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    <p><b>Note: </b>This site is currently experimental. Results are limited to the top 500 most relevant hits.</p>
    <a href="/status">Indices Status</a><br>
    <a href="/advanced_search">Advanced Search (not working yet)</a><br>
    <a href="/elasticsearch/test">Elastic Test page (query for the word "boxing")</a><br><br>
    <p>Updated 12-Jun-2019 by <a href="mailto:skinnear@media24.com">Stuart Kinnear</a></p>
    @endsection