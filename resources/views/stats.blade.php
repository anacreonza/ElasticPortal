@extends('site')

@section('header')
    <title>Archive | Stats</title>
@endsection

@section('content')
    <div class="flex-container">
        <h1>Archive Stats</h1>
        <div class="stats-div">
            <h3>Products</h3>
            <table class="table stats-table">
                <tr><th>Publication</th>
                    @foreach ($data['publication_counts']['aggregations']['Publications']['buckets'] as $pub)
                        <td>{{$pub['key']}}</td>
                    @endforeach
                </tr>
                <tr><th>Count</th>
                    @foreach ($data['publication_counts']['aggregations']['Publications']['buckets'] as $pub)
                        <td>{{$pub['doc_count']}}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        <div class="stats-div">
            <h3>Document Types</h3>
            <table class="table stats-table">
                <tr><th>Document type</th>
                    @foreach ($data['doc_types']['aggregations']['document_types']['buckets'] as $doc)
                        <td>{{$doc['key']}}</td>
                    @endforeach
                </tr>
                <tr><th>Count</th>
                    @foreach ($data['doc_types']['aggregations']['document_types']['buckets'] as $doc)
                        <td>{{$doc['doc_count']}}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        <div class="stats-div">
            <h3>Categories</h3>
            <table class="table stats-table">
                <tr>
                    <th>Category</th>
                    @foreach ($data['categories']['aggregations']['categories']['buckets'] as $cat)
                        <td>{{$cat['key']}}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Count</th>
                    @foreach ($data['categories']['aggregations']['categories']['buckets'] as $cat)
                        <td>{{$cat['doc_count']}}</td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
@endsection