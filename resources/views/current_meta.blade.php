<?php
$terms = Session::get('terms');
?>
@extends('layouts.app')
@section('header')
<title>Current Metadata JSON</title>
@endsection
@section('content')
<div class="container">
    <p><a href="javascript:history.back()">Back</a> | <a href="/">Advanced Search</a></p>
    <h2>Current Metadata:</h2>
    <pre>
    {{dd($metadata)}}
    </pre>
</div>
@endsection