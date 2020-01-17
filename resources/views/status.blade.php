@extends('layouts.app')

<?php
$status_json_url = "http://152.111.25.182:9200/_cat/indices?format=json";
$status_json = file_get_contents($status_json_url);
$status = json_decode($status_json);
sort($status);

#dd($status_array);
/*
health
status
index
uuid
pri
rep
docs.count
docs.deleted
store.size
pri.store.size
*/

?>

@section('header')
    <title>Indices Status</title>
    <meta http-equiv="refresh" content="30" >
@endsection

@section('navbar')
    @component('layouts.navbar')
    @slot('enable_search')
        no
    @endslot
    @endcomponent
@endsection

@section('content')
<div class="container">

    <div class="linkbar"><a href="/home">Back to Admin page</a></div>

    <div class="card">
        <div class="card-header">Indices Status</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Index Name:</th>
                        <th>Status</th>
                        <th>UUID</th>
                        <th>Pri</th>
                        <th>Rep</th>
                        <th>Document Count</th>
                        <th>Deleted Document Count</th>
                        <th>Store Size</th>
                        <th>Primary Store Size</th>
                        <th>Health</th>
                    </tr>
                </thead>
                @foreach ($status as $item)
                    <tr>
                    <td>{{$item->index}}</td>
                    <td>{{$item->status}}</td>
                    <td>{{$item->uuid}}</td>
                    <td>{{$item->pri}}</td>
                    <td>{{$item->rep}}</td>
                    <td>{{$item->{"docs.count"} }}</td>
                    <td>{{$item->{"docs.deleted"} }}</td>
                    <td>{{$item->{"store.size"} }}</td>
                    <td>{{$item->{"pri.store.size"} }}</td>
                        @if ($item->health == "green")
                        <td><span class="green-dot"></span></td>
                        @else
                        <td><span class="red-dot"></span></td>
                        @endif
                    </tr>
                @endforeach
            </table>    
        </div>
    </div>

    <p><b>Note:</b> This page refreshes automatically every 30 seconds.</p>
</div>
@endsection