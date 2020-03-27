@extends('layouts.app')
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')
<div class="container">
    <div class="items-container">
        <h1>Error</h1>
        <p>{{$errorstring}}</p>
    </div>
</div>
@endsection