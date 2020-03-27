<?php
    use \App\Http\Controllers\HealthController;
    $env_status = HealthController::check_env();
    $db_status = HealthController::check_db();
    $elastic_status = HealthController::check_elastic();
?>

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
            <div class="card-header">
                Site Health Check
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr><th>Item</th><th>Status</th></tr>
                    <tr><td>.env file</td><td>
                        @if ($env_status)
                            <span class="ok-block">OK</span>
                        @else
                            <span class="error-block">.env file not found</span>
                        @endif
                    </tr>
                    <tr><td>Database Connection</td><td>
                        @if ($db_status)
                            <span class="ok-block">Database connected</span>
                        @else
                            <span class="error-block">No database connection found.</span>
                        @endif
                    </tr>
                    <tr><td>.configfile</td><td>OK</td></tr>
                    <tr><td>Admin User</td><td>OK</td></tr>
                    <tr><td>Migrations</td><td>OK</td></tr>
                    <tr><td>Elastic Connection</td><td>
                        @if ($elastic_status)
                            <table>
                                <tr><td>Elastic Server Address:</td><td>{{$elastic_status->ip}}:{{$elastic_status->port}}</td></tr>
                                <tr><td>Kibana Address:</td><td>{{$elastic_status->k_ip}}:{{$elastic_status->k_port}}</td></tr>
                                <tr><td>Content Server Address:</td><td>{{$elastic_status->content_ip}}:{{$elastic_status->content_port}}</td></tr>
                            </table>
                            @else
                            <span class="error-block">No elastic connection found.</span>
                        @endif
                    </tr>                </table>
            </div>
        </div>
    </div>
@endsection