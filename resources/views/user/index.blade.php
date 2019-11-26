@extends('layouts.app')
@section('header')
    <title>Archive | Admins</title>
@endsection
@section('navbar')
    @component('layouts.navbar')
    @slot('enable_search')
        no
    @endslot
    @endcomponent
@endsection
@section('content')
    <script>
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
        
        <div class="container">
            @component('user.links')
            @endcomponent
        <div class="items-container">
            <table class="table table-hover">
                <tr>
                    <th>Name</th>
                    <th>email</th>
                    <th>Created</th>
                    <th>Updated</th>
                </tr>
                @foreach ($users as $user)               
                    {{-- <tr class="clickable-row" data-href="./user/edit/{{$user->id}}"> --}}
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->created_at->diffForHumans()}}</td>
                        <td>{{$user->updated_at->diffForHumans()}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{-- <a href="/users/create" class="btn btn-primary" role="button">Create new user</a> --}}
    </div>
@endsection