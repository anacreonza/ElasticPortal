@extends('layouts.app')
@section('header')
<title>Log Viewer</title>
@endsection
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection
@section('content')
<iframe src="/admin/logviewer" frameborder="0" width="100%" height="1200px"></iframe>
@endsection