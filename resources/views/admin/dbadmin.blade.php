@php
    use App\Http\Controllers\DBAdminController;
    $db_name = DBAdminController::check_db();
@endphp
@extends('layouts.app')
@section('header')
<title>Site Health Check</title>
@endsection

@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Database Admin</div>
            <div class="card-body">
                @if ($db_name)
                    <p>Database is located at {{$db_name}}</p>
                @else
                    <p>No Database found!</p>
                @endif
                <ul>
                    <li><a href="/admin/download_db_csv">Download user table as CSV</a></li>
                    <li><a href="/admin/migrate_db">Run Database Migration</a></li>
                    <li></li>
                </ul>
            </div>            
        </div>
    </div>    
@endsection