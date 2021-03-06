@extends('layouts.app')

@section('header')
    <title>Archive | User Home</title>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User Homepage</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @component('user.links')
                        
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
