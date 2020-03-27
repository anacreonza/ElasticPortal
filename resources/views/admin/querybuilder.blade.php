@extends('layouts.app')
@section('header')
    <title>Query Builder</title>
@endsection

@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header" id="head">
                Query Builder
            </div>
            <div class="card-body">
                <button class="btn btn-default" id="add_rule_button">+ Add Rule</button><br>
                <form action="">
                    <div class="query-rule">
                        <select name="" id="" class="form-control">
                            <option value="text">Text Search</option>
                            <option value="date">Date Range</option>
                        </select>
                    </div>
                    <hr>
                    <button class="btn btn-primary">Search</button>
                    <button class="btn btn-basic">Save Query</button>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ URL::asset('js/querybuilder.js') }}"></script>
@endsection