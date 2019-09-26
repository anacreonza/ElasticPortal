@extends('layouts.app')

@section('header')
    <title>Archive | Results</title>
@endsection  

@section('navbar')
    @component('layouts.navbar')
        @slot('enable_search')
            yes
        @endslot
    @endcomponent
@endsection