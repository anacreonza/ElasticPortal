@extends('site')

@section('header')
    <title>Archive | Stats</title>
@endsection

@section('content')
    <div class="flex-container">
        <h1>Archive Stats</h1>
        <div class="stats-div">
            <h3>Categories</h3>
            <table class="table stats-table">
                <tr>
                    <th>Category</th>
                    @foreach ($data['categories'] as $cat)
                        <td>{{$cat['name']}}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>Count</th>
                    @foreach ($data['categories'] as $cat)
                        <td>{{$cat['count']}}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        <iframe src="http://152.111.20.157:5601/app/kibana#/dashboard/35a10dd0-c59f-11e9-b3cc-0503c261002a?embed=true&_g=(refreshInterval%3A(display%3AOff%2Cpause%3A!f%2Cvalue%3A0)%2Ctime%3A(from%3Anow-2y%2Cmode%3Aquick%2Cto%3Anow))" height="1200" width="2000"></iframe>
    </div>
@endsection